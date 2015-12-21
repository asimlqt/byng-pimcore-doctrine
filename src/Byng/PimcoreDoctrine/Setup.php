<?php

namespace Byng\PimcoreDoctrine;

use Pimcore\Config;
use Doctrine\ORM\Tools\Setup as DoctrineSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Cache\Cache;

class Setup
{
    /**
     * 
     * @var array
     */
    private $entityPaths;

    /**
     * 
     * @var boolean
     */
    private $isDevMode;

    /**
     * 
     * @var string
     */
    private $proxyDir;

    /**
     * 
     * @var \Doctrine\Common\Cache\Cache
     */
    private $cache;

    /**
     * 
     * @var \Doctrine\ORM\EntityManager
     */
    private static $em;

    /**
     * Constructor. Initialize required properties.
     * 
     * @param array $entityPaths
     * @param bool  $isDevMode
     *
     * @throws \Byng\PimcoreDoctrine\EntityPathNotFoundException
     */
    public function __construct(array $entityPaths = [], $isDevMode = false)
    {
        if(empty($entityPaths)) {
            throw new EntityPathNotFoundException("Must supply atleast one entities path");
        }

        $this->entityPaths = $entityPaths;
        $this->isDevMode = $isDevMode;
    }

    /**
     * 
     * @param string $proxyDir
     */
    public function setProxyDir($proxyDir)
    {
        $this->proxyDir = $proxyDir;
    }

    /**
     * 
     * @param \Doctrine\Common\Cache\Cache $cache
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Initialize the entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function init()
    {
        $config = DoctrineSetup::createAnnotationMetadataConfiguration(
            $this->entityPaths,
            $this->isDevMode,
            $this->proxyDir,
            $this->cache,
            false
        );
        
        self::$em = EntityManager::create($this->getDbParams(), $config);

        $platform = self::$em->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        return self::$em;
    }

    /**
     * get the configured entity manager. Must call init() first to
     * create it.
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public static function getEntityManager()
    {
        return self::$em;
    }

    /**
     * 
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
