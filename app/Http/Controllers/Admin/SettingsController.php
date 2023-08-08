<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\SettingsImagesTrait;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends AdminController
{
    use SettingsImagesTrait;

    public function index(): Response
    {
        /** @var Collection $settings */
        $settings = Setting::getData();

        return Inertia::render('Admin/Settings/List', [
            'settings' => $settings,
            'title' => 'Settings',
            'active_page' => 'settings',
        ]);
    }

    public function update(Request $request): void
    {
        $settings = $request->all();

        foreach ($settings as $key => $val) {
            Setting::query()->updateOrCreate(['name' => $key], ['value' => $val]);
        }

        $this->success('Settings updated successfully.');
    }
}
