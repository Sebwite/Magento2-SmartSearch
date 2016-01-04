# Magento 2.0 SmartSearch
Magento 2 SmartSearch implementation. This is a basic extension without any config options that replaces the default Magento autocomplete with a smart search implementation.

## Installation with composer
* Include the repository: composer require sebwite/magento2-smartsearch
* Enable the extension: php bin/magento --clear-static-content module:enable Sebwite_SmartSearch
* Upgrade db scheme: php bin/magento setup:upgrade
* Clear cache

After these steps the smart search will be visible.

##Todo's:
* Retrieve currency symbol from Magento (now € is used)
* Add config page
* Show loading indicator