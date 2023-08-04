<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use App\Models\Priority;
use App\Models\Setting;
use App\Models\Status;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Inertia::share('statuses', fn() => Status::ordered()->get());
            Inertia::share('priorities', fn() => Priority::ordered()->get());

//            Setting::query()->where('name', 'theme.brand_color')->value('value') ?? '#347CE4'
            Inertia::share('theme', [
                'brand_color' => Setting::query()->where('name', 'theme.brand_color')->value('value') ?? '#347CE4',
            ]);

            return $next($request);
        });
    }

    /**
     * Flash a message to the session.
     * @param string $type
     * @param string $message
     * @return void
     */
    public function message(string $type, string $message): void
    {
        request()->session()->flash('message', [
            'type' => $type,
            'message' => $message
        ]);
    }

    public function error(string $message): void
    {
        $this->message('error', $message);
    }

    public function success(string $message): void
    {
        $this->message('success', $message);
    }

    public function perPage(): int
    {
        return request()->per_page ?? 25;
    }
}
