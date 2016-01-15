# Magento 2.0 SmartSearch
![Alt text](header.jpg?raw=true "Type ahead search")

Magento 2 type ahead search implementation. Shows a list of found products under the searchbar without reloading the page.
This is a basic extension without any config options that replaces the default Magento autocomplete with a smart search implementation.

## Screenshot
![Alt text](screenshot.png?raw=true "Magento 2 auto fill search extension")

## Installation with composer
* Include the repository: `composer require sebwite/magento2-smartsearch`
* Enable the extension: `php bin/magento --clear-static-content module:enable Sebwite_SmartSearch`
* Upgrade db scheme: `php bin/magento setup:upgrade`
* Clear cache

## Installation without composer
* Download zip file of this extension
* Place all the files of the extension in your Magento 2 installation in the folder app/code/Sebwite/SmartSearch
* Enable the extension: `php bin/magento --clear-static-content module:enable Sebwite_SmartSearch`
* Upgrade db scheme: `php bin/magento setup:upgrade`
* Clear cache

After these steps the default Magento 2 searchbar will be transformed into a smartsearch searchbar.

##Todo's:
* Add config page
* Show loading indicator
* Add cache

---
[![Alt text](https://www.sebwite.nl/wp-content/themes/sebwite/assets/images/logo-sebwite.png "Sebwite.nl")](https://sebwite.nl)

