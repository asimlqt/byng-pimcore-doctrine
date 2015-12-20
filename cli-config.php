<?php

require_once 'pimcore/cli/startup.php';

// use Doctrine\Common\ClassLoader;
// $classLoader = new ClassLoader('Doctrine\DBAL\Migrations', PIMCORE_WEBSITE_PATH . '/migrations');
// $classLoader->register();


$entityManager = \Byng\PimcoreDoctrine\Setup::getEntityManager();
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);