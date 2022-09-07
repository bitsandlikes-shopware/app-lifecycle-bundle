<?php

namespace BAL\AppLifecycleBundle\Controller;

use JsonException;
use RuntimeException;
use BAL\AppLifecycleBundle\Event\AppActivatedEvent;
use BAL\AppLifecycleBundle\Event\AppDeactivatedEvent;
use BAL\AppLifecycleBundle\Event\AppDeletedEvent;
use BAL\AppLifecycleBundle\Event\AppInstalledEvent;
use BAL\AppLifecycleBundle\Event\AppLifecycleEvent;
use BAL\AppLifecycleBundle\Event\AppUpdatedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppLifecycleEventController extends AbstractController
{

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    #[Route(path: '/event/app/activated', name: 'shopware_app.event.app.activated')]
    public function appActivated(Request $request) : JsonResponse
    {
        return $this->handleRequest($request, AppActivatedEvent::class);
    }

    #[Route(path: '/event/app/deactivated', name: 'shopware_app.event.app.deactivated')]
    public function appDeactivated(Request $request) : JsonResponse
    {
        return $this->handleRequest($request, AppDeactivatedEvent::class);
    }

    #[Route(path: '/event/app/deleted', name: 'shopware_app.event.app.deleted')]
    public function appDeleted(Request $request) : JsonResponse
    {
        return $this->handleRequest($request, AppDeletedEvent::class);
    }

    #[Route(path: '/event/app/installed', name: 'shopware_app.event.app.installed')]
    public function appInstalled(Request $request) : JsonResponse
    {
        return $this->handleRequest($request, AppInstalledEvent::class);
    }

    #[Route(path: '/event/app/updated', name: 'shopware_app.event.app.updated')]
    public function appUpdated(Request $request) : JsonResponse
    {
        return $this->handleRequest($request, AppUpdatedEvent::class);
    }

    protected function handleRequest(Request $request, string $eventClass) : JsonResponse
    {
        try {
            $content = $request->getContent();
            if (empty($content)) {
                throw new RuntimeException('Empty body content');
            }
            if ( !is_string($content)) {
                throw new RuntimeException('Invalid body content');
            }
            try {
                $content = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $exception) {
                throw new RuntimeException('Invalid body content');
            }
            if ( !is_array($content)) {
                throw new RuntimeException('Invalid body content');
            }
        } catch (RuntimeException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        /** @var AppLifecycleEvent $event */
        $event    = new $eventClass();
        $response = new JsonResponse(null, Response::HTTP_OK);

        $event = $this->eventDispatcher->dispatch(
            $event->assign(
                [
                    'data'        => $content,
                    'response'    => $response,
                    'appVersion'  => $content['source']['appVersion'],
                    'shopEventId' => $content['source']['eventId'],
                    'shopId'      => $content['source']['shopId'],
                    'shopUrl'     => $content['source']['url'],
                ]
            ),
            $event::NAME
        );

        return $event->getResponse();
    }

}
