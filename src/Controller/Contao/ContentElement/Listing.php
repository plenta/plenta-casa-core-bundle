<?php

declare(strict_types=1);

namespace Plenta\CasaCoreBundle\Controller\Contao\ContentElement;

/**
 * @package       Customer
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

use Contao\BackendTemplate;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\PageModel;
use Contao\Pagination;
use Contao\StringUtil;
use Contao\System;
use Contao\Template;
use Plenta\CasaCoreBundle\Events\FilterParameterEvent;
use Plenta\CasaCoreBundle\Events\KeywordsEvent;
use Plenta\CasaCoreBundle\Filter\Parameter;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsContentElement(type: 'plenta_casa_listing', category: 'plentacasa')]
class Listing extends AbstractContentElementController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
            $objTemplate = new BackendTemplate('be_wildcard');
            $objTemplate->wildcard = $this->translator->trans('CTE.plenta_casa_listing.0', [], 'contao_default');

            return $objTemplate->getResponse();
        }

        $parameters = [];
        $keywordSearch = ['title', 'city', 'description', 'equipment', 'location', 'other'];

        $keywordEvent = new KeywordsEvent($keywordSearch, $model);
        $this->eventDispatcher->dispatch($keywordEvent, $keywordEvent::NAME);

        if ($filter = $request->query->all('filter')) {
            if ($filter['marketingType'] ?? null) {
                $parameters[] = new Parameter(
                    'marketingType',
                    ['marketing_type = ?'],
                    [$filter['marketingType']],
                );
            }

            if ($filter['objectType'] ?? null) {
                $parameters[] = new Parameter(
                    'objectType',
                    ['object_type = ?'],
                    [$filter['objectType']],
                );
            }

            if ($filter['type'] ?? null) {
                $parameters[] = new Parameter(
                    'type',
                    ['type = ?'],
                    [$filter['type']],
                );
            }

            if ($filter['keyword'] ?? null) {
                $columns = [];
                $values = [];

                if ($keywordEvent->isSplitKeywords()) {
                    $keywords = StringUtil::trimsplit(' ', $filter['keyword']);
                } else {
                    $keywords = [trim($filter['keyword'])];
                }

                $criteria = [];

                foreach ($keywords as $keyword) {
                    $kCriteria = [];

                    foreach ($keywordEvent->getFields() as $field) {
                        $kCriteria[] = $field.' LIKE ?';
                        $values[] = '%'.$keyword.'%';
                    }

                    $criteria[] = '('.implode(' OR ', $kCriteria).')';
                }

                if ($keywordEvent->getConnector() === 'AND') {
                    $columns[] = '('.implode(' AND ', $criteria).')';
                } else {
                    $columns[] = '('.implode(' OR ', $criteria).')';
                }

                $parameters[] = new Parameter(
                    'keyword',
                    $columns,
                    $values,
                );
            }
        }

        $event = new FilterParameterEvent($model, $parameters);
        $this->eventDispatcher->dispatch($event, $event::NAME);
        $columns = [];
        $values = [];

        foreach ($event->getParameters() as $parameter) {
            $columns = array_merge($columns, $parameter->getColumns());
            $values = array_merge($values, $parameter->getValues());
        }

        if ($model->perPage) {
            $count = ObjectModel::countPublishedBy($columns, $values);

            $pageParam = 'page_' . $model->id;
            $page = $request->get($pageParam, 1);
            $offset = ($page - 1) * $model->perPage;
            $perPage = $model->perPage;
            $pagination = new Pagination($count, $perPage, 7, $pageParam);
            $template->pagination = $pagination->generate();
        } else {
            $perPage = $model->numberOfItems ?: null;
            $offset = null;
        }

        $objects = ObjectModel::findPublishedBy($columns, $values, [
            'limit' => $perPage,
            'offset' => $offset,
        ]);

        $template->objects = $objects;
        $template->jumpTo = PageModel::findByIdOrAlias($model->jumpTo);

        return $template->getResponse();
    }
}
