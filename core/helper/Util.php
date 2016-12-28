<?php

namespace Core\Helper;

abstract class Util {
    public static function composerDumpAutoload(){
        return exec('composer dump-autoload -d '.getenv('BASE_DIR'));
    }

    public static function fieldsToMigration($fields){
        $rows = '';
        foreach($fields as $field => $type)
            $rows .= '              ->addColumn(\''.$field.'\', \''.$type.'\')' . PHP_EOL;
        return $rows;
    }
}