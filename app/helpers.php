<?php

if (!function_exists('ids')) {
    function ids(array $items): array
    {
        return array_map(function ($item) {
            return $item['id'];
        }, $items);
    }
}
