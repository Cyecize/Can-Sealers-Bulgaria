<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/16/2018
 * Time: 5:05 PM
 */

namespace App\Utils;


use App\Exception\IllegalArgumentException;
use Symfony\Component\Yaml\Yaml;

class YamlParser
{

    public static function getFile(string $path = null): array
    {
        if ($path == null) {
            throw new IllegalArgumentException("Path cannot be null!");
        }

        return Yaml::parse(file_get_contents($path));
    }

    public static function saveFile(array $params, string $path): void
    {
        $yamlFile = YAML::dump($params);
        file_put_contents($path, $yamlFile);
    }
}

