<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Providers;

use App\Models\Priority;
use App\Models\Setting;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use URL;

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
     * @noinspection PhpUndefinedMethodInspection
     */
    public function boot(): void
    {
        URL::forceScheme(config('app.url_scheme'));

        Request::macro('ids', function (string $field): array {
            return collect($this->input($field))->map(fn($row) => $row['id'])->toArray();
        });

        $settings = Setting::getData();
        view()->composer('*', function ($view) use ($settings) {
            $view->with([
                'settings' => $settings,
            ]);
        });

//        try {
//            Inertia::share('statuses', fn() => Status::ordered()->get());
//            Inertia::share('priorities', fn() => Priority::ordered()->get());
//        } catch (Throwable $e) {
//            dd($e);
//        }
    }
}
