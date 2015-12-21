# Pimcore doctrine plugin

This plugin allows developers to use doctrine orm to manage custom objects which are not handled by pimcore.

# Usage

## Installation

1 - Require the library in composer.json
```
"require": {
    "asimlqt/byng-pimcore-doctrine": "dev-master"
}
```
You will also need to add a post-install script to install the cli script.
```
"scripts": {
    "post-install-cmd": "Byng\\PimcoreDoctrine\\Composer\\CliManager::postInstall"
}
```

2 - Setup the library
Add the following to 'website/var/config/startup.php'. Set the $entityDir to wherever you wish to create your entities.
```
$entityDir = PIMCORE_DOCUMENT_ROOT . "/website/lib/Byng/Entity";
$setup = new \Byng\PimcoreDoctrine\Setup([$entityDir]);
$em = $setup->init();
```

3 - Test
Open a terminal and 'cd' to your document root and run the following command:
```
./vendor/bin/doctrine
```
You should see a list of available commands