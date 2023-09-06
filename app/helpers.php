<?php

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

if (!function_exists('loggedUser')) {
    function loggedUser(): User|Authenticatable|null
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

//function timeBetweenTwoDates($date1, $date2) {
//    $timestamp1 = strtotime($date1);
//    $timestamp2 = strtotime($date2);
//
//    $seconds = abs($timestamp2 - $timestamp1);
//    $minutes = floor($seconds / 60);
//    $hours = floor($minutes / 60);
//
//    $parts = [];
//    if ($hours) {
//        $parts[] = str_pad($hours, 1, '0', STR_PAD_LEFT) . '<span style="opacity: 0.5;">h</span>';
//    }
//    if ($minutes % 60) {
//        $parts[] = str_pad($minutes % 60, 2, '0', STR_PAD_LEFT) . '<span style="opacity: 0.5;">m</span>';
//    }
//    $parts[] = str_pad($seconds % 60, 2, '0', STR_PAD_LEFT) . '<span style="opacity: 0.5;">s</span>';
//
//    return implode(' ', $parts);
//}

function secondsToTime(?int $seconds = 0, bool $html = true): string
{
    $minutes = floor($seconds / 60);
    $hours = floor($minutes / 60);

    $parts = [];
    if ($hours) {
        $parts[] = str_pad($hours, 1, '0', STR_PAD_LEFT) . '<span style="opacity: 0.5;">h</span>';
    }
    if ($minutes % 60) {
        $parts[] = str_pad($minutes % 60, 2, '0', STR_PAD_LEFT) . '<span style="opacity: 0.5;">m</span>';
    }
    $parts[] = str_pad($seconds % 60, 2, '0', STR_PAD_LEFT) . '<span style="opacity: 0.5;">s</span>';

    $string = implode(' ', $parts);

    if(!$html) {
        $string = strip_tags($string);
    }

    return $string;
}
