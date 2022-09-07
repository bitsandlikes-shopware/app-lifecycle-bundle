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

## Subscribe to app lifecycle events
Create a subscriber (see Symfony documentation) and subscribe to the following possible events
```
BAL\AppLifecycleBundle\Event\AppActivatedEvent;
BAL\AppLifecycleBundle\Event\AppDeactivatedEvent;
BAL\AppLifecycleBundle\Event\AppDeletedEvent;
BAL\AppLifecycleBundle\Event\AppInstalledEvent;
BAL\AppLifecycleBundle\Event\AppUpdatedEvent;
```

Example-Subscriber
```
// path: <project-root-dir>/EventSubscriber/AppLifecycleEventSubscriber.php
<?php

namespace <project-root-namespace>\EventSubscriber;

use BAL\AppLifecycleBundle\Event\AppActivatedEvent;
use BAL\AppLifecycleBundle\Event\AppDeactivatedEvent;
use BAL\AppLifecycleBundle\Event\AppDeletedEvent;
use BAL\AppLifecycleBundle\Event\AppInstalledEvent;
use BAL\AppLifecycleBundle\Event\AppUpdatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: AppActivatedEvent::NAME, method: 'onActivatedEvent')]
#[AsEventListener(event: AppDeactivatedEvent::NAME, method: 'onDeactivatedEvent')]
#[AsEventListener(event: AppDeletedEvent::NAME, method: 'onDeletedEvent')]
#[AsEventListener(event: AppInstalledEvent::NAME, method: 'onInstalledEvent')]
#[AsEventListener(event: AppUpdatedEvent::NAME, method: 'onUpdatedEvent')]
class AppLifecycleEventSubscriber
{

    public function onActivatedEvent(AppActivatedEvent $event)
    {
        // handle app.activated event
    }

    public function onDeactivatedEvent(AppDeactivatedEvent $event)
    {
        // handle app.deactivated event
    }

    public function onDeletedEvent(AppDeletedEvent $event)
    {
        // handle app.deleted event
    }

    public function onInstalledEvent(AppInstalledEvent $event)
    {
        // handle app.installed event
    }

    public function onUpdatedEvent(AppUpdatedEvent $event)
    {
        // handle app.updated event
    }

}
```
