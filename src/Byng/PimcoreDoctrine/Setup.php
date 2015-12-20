<?php

namespace Byng\PimcoreDoctrine;

use Pimcore\Config;
use Doctrine\ORM\Tools\Setup as DoctrineSetup;
use Doctrine\ORM\EntityManager;

class Setup
{

    private $entityPaths;
    private $isDevMode;

    /**
     * 
     * @var \Doctrine\ORM\EntityManager
     */
    private static $em;

    /**
     * [__construct description]
     * 
     * @param array $entityPaths
     * @param bool  $isDevMode
     */
    public function __construct(array $entityPaths = [], $isDevMode = false)
    {
        if(empty($entityPaths)) {
            throw new Exception("Must supply an enetties");
        }

        $this->entityPaths = $entityPaths;
        $this->isDevMode = $isDevMode;
    }

    /**
     * [setDevMode description]
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function init()
    {
        $config = DoctrineSetup::createAnnotationMetadataConfiguration(
            $this->entityPaths,
            $this->isDevMode
        );
        
        self::$em = EntityManager::create($this->getDbParams(), $config);
        return self::$em;
    }

    /**
     * [getEntityManager description]
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public static function getEntityManager()
    {
        return self::$em;
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
            'driver'   => strtolower($db->adapter),
            'user'     => $db->params->username,
            'password' => $db->params->password,
            'dbname'   => $db->params->dbname,
        );
    }

}
