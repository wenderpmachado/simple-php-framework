<?php

namespace Core\Helper;

abstract class ExecUtil {
    public static function composerDumpAutoload(){
        return exec('composer dump-autoload -d '.getenv('BASE_DIR'));
    }

    public static function createMigration($className, $templateUrl = 'core/database/CreateTableMigration.template.php.dist', $configurationUrl = 'phinx.php'){
        return exec('php '.getenv('BASE_DIR').'vendor/bin/phinx create '.$className.' --template='.getenv('BASE_DIR').$templateUrl.' --configuration='.getenv('BASE_DIR').$configurationUrl);
    }
}