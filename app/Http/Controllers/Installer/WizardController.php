<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\InstallerServiceProvider;
use Auth;
use Exception;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class WizardController //extends Controller
{
    use ValidatesRequests;

    public function index(): Response
    {
        return Inertia::render('Installer/Wizard', [
            'requirments' => $this->checkServerRequirments(),
            'permissions' => $this->checkFolderPermissions(),
            'env' => $this->getEnv(),
            'app_key' => 'base64:' . base64_encode(random_bytes(32)),
        ]);
    }

    public function processStep(int $step, Request $request): JsonResponse
    {
        if ($step === 0) { // requirments

        } elseif ($step === 1) { // permissinos

        } elseif ($step === 2) { // database
            try {
                $db = $request->get('db');
                $timeout = 3;
                $link = mysqli_init( );
                $link->options( MYSQLI_OPT_CONNECT_TIMEOUT, $timeout );
                $link->real_connect($db['host'], $db['username'], $db['password'], $db['database'], $db['port']);

                // check if database is empty
                $result = $link->query("SHOW TABLES FROM " . $db['database']);
                if ($result->num_rows > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Database is not empty',
                    ]);
                }

            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }

            return response()->json([
                'success' => true,
            ]);
        } elseif ($step === 3) { // account
            try {
                $this->validate($request, [
                    'account.name' => 'required|string|max:255',
                    'account.email' => 'required|string|email|max:255',
                    'account.password' => 'required|string|min:6|confirmed',
                ], [], [
                    'account.name' => 'Name',
                    'account.email' => 'Email',
                    'account.password' => 'Password',
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }

        } elseif ($step === 4) { // environment
            try {
                $env = $request->get('env');
                file_put_contents(base_path('.env'), $env);
                // run migrations
                Artisan::call('migrate:fresh', ['--force' => true]);

                // run seeders
                Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--force' => true]);

                // Create admin user
                $user = User::query()->create([
                    'name' => $request->get('account')['name'],
                    'email' => $request->get('account')['email'],
                    'password' => bcrypt($request->get('account')['password']),
                ]);
                $user->groups()->attach(1);

                // Create installer lock file
                file_put_contents(base_path(InstallerServiceProvider::INSTALLER_LOCK_FILE), '');
            } catch (Throwable $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        } elseif ($step === 5) { // finish
            Auth::login(
                User::query()->where('email', $request->get('account')['email'])->firstOrFail(),
            );
        }

        return response()->json([
            'success' => true,
        ]);
    }

    private function checkServerRequirments(): array
    {
        // Get composer.json

        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        $json = collect($composer['require']);

        // Check for php extensions

        $requirments = $json->filter(fn($item, $key) => str_contains($key, 'ext-'));

        $requirments = $requirments->map(function ($item, $key) {
            $extension = str_replace('ext-', '', $key);
            return [
                'name' => $key,
                'ok' => in_array($extension, get_loaded_extensions())
            ];
        });

        // Check for php version

        $php = $json->filter(fn($item, $key) => $key === 'php');
        $phpVersion = phpversion();

        $requirments->put('php ' . $php->first(), [
            'name' => 'php ' . $php->first(),
            'ok' => version_compare($phpVersion, $php->first(), '>=')
        ]);

        return $requirments->reverse()->values()->toArray();
    }

    private function checkFolderPermissions(): array
    {
        $folders = [
            'storage/framework/' => '775',
            'storage/logs/' => '775',
            'bootstrap/cache/' => '775',
        ];

        $permissions = [];

        foreach ($folders as $folder => $permission) {
            $writable = is_writable(base_path($folder));
            $permissions[] = [
                'folder' => $folder,
                'permission' => $permission,
                'writable' => $writable,
            ];
        }

        return $permissions;
    }

    private function getEnv()
    {
        $content = file_get_contents(base_path('.env.example'));
        // remove comments
        $content = preg_replace('/#.*/', '', $content);
        // remove empty lines
        $content = preg_replace('/\n\s*\n/', "\n", $content);

        return $content;
    }
}
