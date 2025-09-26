<?php

namespace Plenta\CasaCoreBundle\Controller;

use Contao\CoreBundle\Controller\AbstractController;
use Plenta\CasaCoreBundle\Events\FilterTypeOptionsEvent;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\CasaCoreBundle\Model\TypeModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('_casa', defaults: ['_scope' => 'frontend'])]
class AjaxController extends AbstractController
{
    #[Route('/types/{id}')]
    public function getTypes(int $id, EventDispatcherInterface $eventDispatcher): JsonResponse
    {
        $types = TypeModel::findByPid($id);
        $return = [];

        foreach ($types as $type) {
            if (ObjectModel::countPublishedBy(['type = ?'], [$type->id]) > 0) {
                $return[$type->id] = $type->title;
            }
        }

        $event = new FilterTypeOptionsEvent($return);
        $eventDispatcher->dispatch($event, FilterTypeOptionsEvent::NAME);

        return new JsonResponse($event->getOptions());
    }
}