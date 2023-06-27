<?php

namespace App\Providers;

use App\Models\Priority;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Request::macro('ids', function (string $field): array {
            return collect($this->input($field))->map(fn($row) => $row['id'])->toArray();
        });

        Inertia::share('statuses', fn() => Status::all());
        Inertia::share('priorities', fn() => Priority::all());
    }
}
