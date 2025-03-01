<?php

declare(strict_types=1);

namespace PhpMyAdmin\Utils;

final class SessionCache
{
    private static function key(): string
    {
        $GLOBALS['server'] ??= null;
        $key = 'server_' . $GLOBALS['server'];

        if (isset($GLOBALS['cfg']['Server']['user'])) {
            return $key . '_' . $GLOBALS['cfg']['Server']['user'];
        }

        return $key;
    }

    public static function has(string $name): bool
    {
        return isset($_SESSION['cache'][self::key()][$name]);
    }

    public static function get(string $name, callable|null $defaultValueCallback = null): mixed
    {
        if (self::has($name)) {
            return $_SESSION['cache'][self::key()][$name];
        }

        if ($defaultValueCallback !== null) {
            $value = $defaultValueCallback();
            self::set($name, $value);

            return $value;
        }

        return null;
    }

    /** @param mixed $value */
    public static function set(string $name, $value): void
    {
        $_SESSION['cache'][self::key()][$name] = $value;
    }

    public static function remove(string $name): void
    {
        unset($_SESSION['cache'][self::key()][$name]);
    }
}
