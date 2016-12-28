<?php

namespace Core\Helper;

abstract class Util {
    public static function composerDumpAutoload(){
        return exec('composer dump-autoload -d '.getenv('PROJECT_PATH'));
    }
}