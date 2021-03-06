<?php

namespace Supernova;

/** 
 * Cache del modelo de las tablas
 */
class Cache
{
    /**
     * Carga cache del modelo
     * @param  string $model Nombre del modelo
     * @return string        Esquema del modelo desencriptado
     */
    public static function load($model)
    {
        $filename = ROOT.DS."Cache".DS.$model;
        if (!file_exists($filename)) {
            self::generate($model);
        }
        parse_str(\Supernova\Crypt::decrypt(file_get_contents($filename)), $output);
        return $output;
    }

    /**
     * Genera cache del modelo
     * @param  string $model Nombre del modelo
     * @return null
     */
    public static function generate($model)
    {
        $dirName = ROOT.DS.'Cache';
        if (!file_exists($dirName)) {
            mkdir($dirName, 0777, true);
        }
        chdir($dirName);
        $table = \Supernova\Inflector::camelToUnder(\Supernova\Inflector::pluralize($model));
        $fields = \Supernova\Sql::getFields($table);
        file_put_contents($model, \Supernova\Crypt::encrypt(http_build_query($fields)));
        chmod($model, 0777);
    }
}
