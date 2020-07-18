<?php

namespace App\Helper;

use Throwable;

class ExceptionFormatter
{
    /**
     * @param string $message
     * @param int $depth
     * @return string
     */
    public static function f(string $message, int $depth = 1): string
    {
        $tree = debug_backtrace();
        if (true === key_exists($depth, $tree)) {
            $node = $tree[$depth];
            $message = "{$node['class']}->{$node['function']}: {$message}";
        }

        return $message;
    }

    /**
     * @param Throwable $e
     * @return string
     */
    public static function e(Throwable $e): string
    {
        $result = ["{message} {$e->getMessage()}"];
        foreach ($e->getTrace() as $key => $stack) {
            $key++;
            $result[] = "#{$key} {$stack['file']}({$stack['line']}): {$stack['class']}{$stack['type']}{$stack['function']}()";
        }

        return implode(PHP_EOL, $result);
    }
}