<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class InstallerServiceProvider extends ServiceProvider
{
    const INSTALLER_LOCK_FILE = 'storage/app/install.lock';

    /**
     * Bootstrap any application services.
     * @noinspection PhpUndefinedMethodInspection
     */
    public function boot(): void
    {
        $installeLockFile = base_path(self::INSTALLER_LOCK_FILE);

        if (!file_exists($installeLockFile)) {
            $to = URL::to('installer');

            if (str_contains(URL::current(), $to) === false) {
                header('Location: ' . URL::to($to));
                die;
            }
        }
    }
}
