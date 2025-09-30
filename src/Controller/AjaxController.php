<?php

namespace Plenta\CasaCoreBundle\Controller;

use Contao\ContentModel;
use Contao\Controller;
use Contao\CoreBundle\Controller\AbstractController;
use Plenta\CasaCoreBundle\Events\FilterTypeOptionsEvent;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\CasaCoreBundle\Model\TypeModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('_casa', defaults: ['_scope' => 'frontend'])]
class AjaxController extends AbstractController
{
    #[Route('/types/{id}/{filterId}')]
    public function getTypes(int $id, int $filterId, EventDispatcherInterface $eventDispatcher): JsonResponse
    {
        $types = TypeModel::findByPid($id);
        $return = [];
        $model = ContentModel::findByPk($filterId);
        $countArr = [];
        $all = [];

        if ($types) {
            foreach ($types as $type) {
                if ($count = ObjectModel::countPublishedBy(['type = ?'], [$type->id])) {
                    $return[$type->id] = $type->title;
                }

                $all[$type->id] = $type->title;
                $countArr[$type->id] = $count;
            }
        }

        $event = new FilterTypeOptionsEvent($return, $model, $all, $countArr);
        $eventDispatcher->dispatch($event, FilterTypeOptionsEvent::NAME);

        return new JsonResponse($event->getOptions());
    }

    #[Route('/filter/{id}')]
    public function filter(int $id)
    {
        return new Response(Controller::getContentElement($id));
    }
}