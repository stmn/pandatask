<?php

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

if (!function_exists('loggedUser')) {
    function loggedUser(): User|Authenticatable
    {
        return auth()->user();
    }
}

if (!function_exists('ids')) {
    function ids(array $items): array
    {
        return array_map(function ($item) {
            return $item['id'];
        }, $items);
    }
}
