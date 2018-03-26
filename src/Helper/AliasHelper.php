<?php

namespace TextOnImage\Helper;

/**
 * Class Alias
 * @package TextOnImage\Helper
 */
class AliasHelper
{
    /**
     * @param $aliasPath
     * @return string
     * @throws \Exception
     */
    public static function getPath($aliasPath) : string
    {
        if (empty($aliasPath)) {
            return $aliasPath;
        }

        $alias = explode('/', $aliasPath)[0];
        if (empty($alias)) {
            return $aliasPath;
        }

        if ($alias === $aliasPath || $alias === $aliasPath . '/') {
            $aliases = require __DIR__ . '/../../Config/aliases.php';
            if (!is_array($aliases)) {
                throw new \Exception('Aliases config must be array');
            }
            if (key_exists($alias, $aliases)) {
                return $aliases[$alias];
            } else {
                throw new \Exception("Alias '{$alias}' doesn't exist");
            }
        }

        $aliases = require __DIR__ . '/../../Config/aliases.php';
        if (!is_array($aliases)) {
            throw new \Exception('Aliases config must be array');
        }
        if (key_exists($alias, $aliases)) {
            $str = '/';
            foreach (array_slice(explode('/', $aliasPath), 1) as $part) {
                $str .= $part . '/';
            }
            return rtrim($aliases[$alias] . $str, '/');
        } else {
            throw new \Exception("Alias '{$alias}' doesn't exist");
        }
    }
}