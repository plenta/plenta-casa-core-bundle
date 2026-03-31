<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Controller;

use Contao\ContentModel;
use Contao\Controller;
use Contao\CoreBundle\Controller\AbstractController;
use Plenta\CasaCoreBundle\Events\FilterTypeOptionsEvent;
use Plenta\CasaCoreBundle\Helper\ObjectTypes;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('_casa', defaults: ['_scope' => 'frontend'])]
class AjaxController extends AbstractController
{
    public function __construct(private readonly ObjectTypes $objectTypes)
    {
    }

    #[Route('/types/{id}/{filterId}')]
    public function getTypes(int $id, int $filterId, EventDispatcherInterface $eventDispatcher): JsonResponse
    {
        $return = [];
        $types = $this->objectTypes->getTypes();
        $model = ContentModel::findById($filterId);
        $countArr = [];
        $all = [];

        foreach ($types as $id => $title) {
            if ($count = $this->objectTypes->countObjects($id)) {
                $return[] = [
                    'id' => $id,
                    'title' => $title,
                ];
            }

            $all[] = [
                'id' => $id,
                'title' => $title,
            ];

            $countArr[$id] = $count;
        }

        $event = new FilterTypeOptionsEvent($return, $model, $all, $countArr);
        $eventDispatcher->dispatch($event, FilterTypeOptionsEvent::NAME);

        return new JsonResponse($event->getOptions());
    }

    #[Route('/filter/{id}')]
    public function filter(int $id): Response
    {
        return new Response(Controller::getContentElement($id));
    }
}
