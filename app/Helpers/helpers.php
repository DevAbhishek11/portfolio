<?php

if (! function_exists('active_route')) {
    function active_route(string $route, string $class = 'active'): string
    {
        return request()->routeIs($route) ? $class : '';
    }
}

if (! function_exists('format_bytes')) {
    function format_bytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);
        return round($bytes / (1024 ** $pow), $precision) . ' ' . $units[$pow];
    }
}

if (! function_exists('time_to_read')) {
    function time_to_read(string $content): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($content)) / 200));
    }
}

if (! function_exists('truncate_html')) {
    function truncate_html(string $html, int $limit): string
    {
        $text = strip_tags($html);
        return mb_strlen($text) > $limit
            ? mb_substr($text, 0, $limit) . '…'
            : $text;
    }
}

if (! function_exists('gradient_avatar')) {
    /** Returns inline style for a gradient avatar based on name */
    function gradient_avatar(string $name): string
    {
        $colors = [
            ['#8b5cf6', '#06b6d4'],
            ['#f43f5e', '#8b5cf6'],
            ['#06b6d4', '#22c55e'],
            ['#eab308', '#f43f5e'],
        ];
        $idx = crc32($name) % count($colors);
        [$a, $b] = $colors[abs($idx)];
        return "background:linear-gradient(135deg,{$a},{$b});";
    }
}
