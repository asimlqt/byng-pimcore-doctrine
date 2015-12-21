# Pimcore doctrine plugin

This plugin allows developers to use doctrine orm to manage objects outside of pimcore.

# Usage

## Installation

Add the plugin in composer.json
```
"require": {
    "asimlqt/byng-pimcore-doctrine": "dev-master"
}
```
You will also need to add a post-install script to install the doctrine cli script. If you don't add the following line then you will have to manually copy 'cli-config.php' from inside the plugin folder to your document root.
```
"scripts": {
    "post-install-cmd": "Byng\\PimcoreDoctrine\\Composer\\CliManager::postInstall"
}
```

## Setup

Add the following to 'website/var/config/startup.php'. Set the $entityDir to wherever you wish to create your entities.
```
$entityDir = PIMCORE_DOCUMENT_ROOT . "/website/lib/Entity";
$setup = new \Byng\PimcoreDoctrine\Setup([$entityDir]);
$em = $setup->init();
```
You can store the entity manager reference ($em) in your DI container or Zend_Registry if you wish. You can also retrieve it from the setup class from anywhere in your code base:
```
$em = \Byng\PimcoreDoctrine\Setup::getEntityManager();
```

## Test

Open a terminal and 'cd' to your document root and run the following command:
```
./vendor/bin/doctrine
```
You should see a list of all available doctrine commands

## Example

1. Create a product entity

NB: You'll probably have to add the 'Entity' namespace to your autoloader.

website/lib/Entity/Product.php
```
<?php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 **/
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue 
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
```

2. Create the products table using the doctrine cli

```
./vendor/bin/doctrine orm:schema-tool:update --force
```

3. Create a repository class to handle products

website/lib/Entity/Repository/ProductRepository.php
```
<?php
namespace Entity\Repository;

use Byng\PimcoreDoctrine\AbstractRepository;

class ProductRepository extends AbstractRepository
{
    const ENTITY_CLASS = "Entity\\Product";
    
    /**
     *
     * @return string
     */
    protected function getEntityClass()
    {
        return static::ENTITY_CLASS;
    }
}

```

4. Finally we can write code to persist our entity

```
<?php
use Entity\Repository\ProductRepository;
use Byng\PimcoreDoctrine\Setup;
use Entity\Product;

$product = new Product();
$product->setName("Test");

$repository = new ProductRepository(Setup::getEntityManager());
$repository->save($product);
```