<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

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
