<?php

namespace Core\Helper;

abstract class ExecUtil {
    public static function composerDumpAutoload(){
        return exec('composer dump-autoload -d '.getenv('BASE_DIR'));
    }

    public static function createMigration($className, $templateFile = 'CreateTableMigration.template.php.dist', $configurationUrl = 'phinx.php'){
		$templateUrl = getenv('BASE_DIR') . getenv('DATABASE_DIR') . $templateFile;
    	$command = getenv('BASE_DIR').'vendor/bin/phinx create '.$className.' --template='.$templateUrl.' --configuration='.getenv('BASE_DIR').$configurationUrl;
        return exec($command);
    }
}
