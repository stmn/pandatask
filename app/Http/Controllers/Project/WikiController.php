<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Project;

use App\Models\Page;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class WikiController extends ProjectController
{
    public function index(Project $project, ?Page $page = null): Response
    {
        return Inertia::render('Project/Wiki', [
            'activeTab' => 'pages',
            'project' => fn() => $project,
            'page' => function () use ($project, $page) {
                if ($page) {
                    $page->load('updatedBy');
                    return $page;
                }

                return $project->pages()
                    ->with(['updatedBy'])
                    ->orderByRaw("slug_title = '".Page::HOME."' desc")
                    ->orderBy('title')
                    ->first();
            },
            'pages' => fn() => $project->pages()
                ->orderByRaw("slug_title = '".Page::HOME."' desc")
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function save(Request $request, Project $project, ?Page $page = null)
    {
        $validated = $request->validate([
            'title' => ['required', Rule::unique('pages')->ignore($page?->id)->where('project_id', $project->id)],
            'content' => [],
        ]);

        $values = collect($validated);

        if ($page?->slug_title === Page::HOME) {
            $values->forget('title');
        } else {
            $values->put('slug_title', Str::slug($values['title']));
        }
        $values->put('updated_by', auth()->id());

        $page = $project->pages()->updateOrCreate([
            'id' => $page?->id,
        ], $values->toArray());

        assert($page instanceof Page);

        $this->success($page->wasRecentlyCreated ? 'Page added' : 'Page updated');

        return redirect()->route('project.pages.show', ['project' => $project, 'page' => $page]);
    }

    public function delete(Project $project, Page $page): RedirectResponse
    {
        $page->delete();

        $homePage = $project->pages()->where('slug_title', 'home')->first();

        $this->success('Page deleted');

        return redirect()->route('project.pages.show', ['project' => $project, 'page' => $homePage]);
    }
}
