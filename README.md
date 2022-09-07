# Shopware app server: App lifecycle bundle

Currently under development. Current code in dev Branch.

## Installation
```
composer require bitsandlikes-shopware/app-lifecycle-bundle
```
Add to config/routes.yaml:
```
bal_app_lifecycle_bundle:
    resource: "@ShopwareAppLifecycleBundle/Controller/"
    type:     annotation
```
Add to config/bundles.php:
```
BAL\AppLifecycleBundle\ShopwareAppLifecycleBundle::class => ['all' => true],
```
