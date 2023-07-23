<?php

namespace App\Models;

use App\QueryBuilders\PageQueryBuilder;
use App\QueryBuilders\ProjectQueryBuilder;
use App\QueryBuilders\UserQueryBuilder;
use DOMDocument;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperTime
 * @property string $content
 * @property string $converted_content
 * @property string $slug_title
 */
class Page extends Model
{
    const HOME = 'home';

    protected $fillable = [
        'project_id', 'title', 'slug_title', 'content', 'updated_by'
    ];

    protected $casts = [];

    protected $appends = [
        'headers', 'converted_content'
    ];

    public static function query(): Builder|PageQueryBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): PageQueryBuilder
    {
        return new PageQueryBuilder($query);
    }

    // Attributes

    public function getConvertedContentAttribute(): string
    {
        return $this->content ? $this->addHeaderIdsToHtml($this->content) : '';
    }

    public function getHeadersAttribute(): array
    {
        $headers = $this->parseHeaders($this->converted_content);
        $allLevels = array_map(fn($header) => $header['level'], $headers);
        if (!$allLevels) {
            return $allLevels;
        }
        $minLevel = min($allLevels);

        return array_map(function ($header) use ($minLevel) {
            $header['level'] -= $minLevel - 1;
            return $header;
        }, $headers);
    }

    private function parseHeaders(string $html): array
    {
        $headers = [];

        preg_match_all('/<h([1-6]).*?id="(.*?)".*?>(.*?)<\/h\1>/i', $html, $matches);

        foreach ($matches[0] as $key => $value) {
            $headers[] = [
                'level' => $matches[1][$key],
                'id' => $matches[2][$key],
                'title' => $matches[3][$key],
            ];
        }

        return $headers;
    }

    private function addHeaderIdsToHtml(string $html = ''): string
    {
        // Tworzenie instancji obiektu DOMDocument
        $dom = new DOMDocument();

        // Ignorowanie ostrzeżeń dotyczących niepoprawnego kodu HTML
        libxml_use_internal_errors(true);
        // Ładowanie kodu HTML
        $dom->loadHTML($html);
        // Przełączenie na tryb zwracania oryginalnych znaczników zamiast uproszczonego HTML
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        // Pobieranie wszystkich nagłówków (znaczników <h1> do <h6>)
        $headers = [];
        foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $tag) {
            foreach ($dom->getElementsByTagName($tag) as $header) {
                $headers[] = $header;
            }
        }

        // Przechodzenie przez wszystkie nagłówki i dodawanie ID zgodnie z treścią nagłówka
        foreach ($headers as $header) {
            $headerText = $header->textContent;
            // Usuwanie niepotrzebnych znaków z treści nagłówka
            $id = preg_replace('/[^a-z0-9]+/', '-', strtolower($headerText));
            $id = trim($id, '-');
            // Dodawanie ID do nagłówka
            $header->setAttribute('id', $id);
        }

        // Pobieranie zmodyfikowanego kodu HTML
        $modifiedHtml = $dom->saveHTML();
        // Usuwanie nadmiarowych tagów <html> i <body>
        $modifiedHtml = preg_replace('/^<!DOCTYPE.+?<html><body>/', '', $modifiedHtml);
        return preg_replace('/<\/body><\/html>$/', '', $modifiedHtml);
    }

    // Relations

    public function project(): BelongsTo|ProjectQueryBuilder
    {
        return $this->belongsTo(Project::class);
    }

    public function updatedBy(): BelongsTo|UserQueryBuilder
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Actions
}
