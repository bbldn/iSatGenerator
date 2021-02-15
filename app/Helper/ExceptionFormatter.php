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
        $className = get_class($e);
        $result = ["{message} {$className}:{$e->getMessage()}"];
        foreach ($e->getTrace() as $key => $stack) {
            $key++;
            $line = "#{$key} ";
            $line .= $stack['file'] ?? 'FILE ';
            $line .= true === key_exists('line', $stack) ? "({$stack['line']}): " : '(LINE): ';
            $line .= $stack['class'] ?? 'CLASS ';
            $line .= $stack['type'] ?? 'TYPE ';
            $line .= true === key_exists('function', $stack) ? "{$stack['function']}()" : 'FUNCTION()';
            $result[] = $line;
        }

        return implode(PHP_EOL, $result);
    }
}
