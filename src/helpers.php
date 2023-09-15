<?php

declare(strict_types=1);

if (! function_exists('str_squish')) {
    function str_squish(string $string, string $replacement = ' '): string {
        return preg_replace('/(?:\s|&nbsp;)+/', $replacement, $string);
    }
}
