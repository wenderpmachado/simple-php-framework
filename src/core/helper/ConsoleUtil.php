<?php

namespace Core\Helper;

use \Core\Helper\ConsoleUtil;

abstract class ConsoleUtil {
    public static function writeStartExecute(OutputInterface $output, $className) {
        $output->writeln([
            '============',
            $className,
            '============',
            ''
        ]);
        
        $output->writeln([
            'Executing...'
        ]);
    }

    public static function writeSuccess(OutputInterface $output, boolean $success) {
        if($sucess > 1) {
            $output->writeln([
                'Sucess!',
                '============',
                '',
            ]);
        } else {
            $output->writeln([
                'Fail! :(',
                '============',
                '',
            ]);
        }
    }

    public static function argumentToParameters(array $parameters) {
        $parametersInArray = [];
        foreach($parameters as $parameter) {
            $parameterExploded = expode(':', $parameter);
            $parametersInArray[$parameterExploded[0]] = $parameterExploded[1];
        }
        
        return $parametersInArray;
    }
}