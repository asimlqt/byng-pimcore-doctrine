<?php

require_once 'pimcore/cli/startup.php';

$entityManager = \Byng\PimcoreDoctrine\Setup::getEntityManager();
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);