<?php

namespace Byng\PimcoreDoctrine\Composer;

use Composer\Script\Event;
use Composer\Util\Filesystem;
use Composer\Installer\PackageEvent;

class CliManager
{

    public static function postInstall(Event $event)
    {
        $config = $event->getComposer()->getConfig();
        $rootPath = dirname($config->get('vendor-dir'));

        if(!file_exists($rootPath."/cli-config.php")) {
            copy(
                $rootPath . "/vendor/asimlqt/byng-pimcore-doctrine/cli-config.php",
                $rootPath . "/cli-config.php"
            );
        }
    }

}