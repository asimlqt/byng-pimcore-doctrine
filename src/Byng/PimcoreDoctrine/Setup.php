<?php

namespace Byng\PimcoreDoctrine;

use Pimcore\Config;
use Doctrine\ORM\Tools\Setup as DoctrineSetup;
use Doctrine\ORM\EntityManager;

class Setup
{
    
    private $entityPaths = [];
    private $isDevMode = false;

    /**
     * [__construct description]
     * 
     * @param array $entityPaths [description]
     */
    public function __construct(array $entityPaths = [])
    {
        $this->entityPaths = $entityPaths;
    }

    /**
     * [setDevMode description]
     * 
     * @param bool $isDevMode [description]
     */
    public function setDevMode($isDevMode)
    {
        $this->isDevMode = $isDevMode;
    }

    /**
     * [getEntityManager description]
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        $config = DoctrineSetup::createAnnotationMetadataConfiguration(
            $this->entityPaths,
            $this->isDevMode
        );
        
        return EntityManager::create($this->getDbParams(), $config);
    }

    /**
     * [getDbParams description]
     * 
     * @return array
     */
    private function getDbParams()
    {
        $config = Config::getSystemConfig();
        $db = $config->database;

        return array(
            'driver'   => $db->adapter,
            'user'     => $db->params->username,
            'password' => $db->params->password,
            'dbname'   => $db->params->dbname,
        );
    }

}
