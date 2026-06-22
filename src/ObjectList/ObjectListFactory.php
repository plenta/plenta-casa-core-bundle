<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\ObjectList;

use Contao\ContentModel;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Pagination\PaginationConfig;
use Contao\CoreBundle\Pagination\PaginationFactoryInterface;
use Plenta\CasaCoreBundle\Filter\ParameterGenerator;
use Plenta\CasaCoreBundle\Model\ObjectModel;

class ObjectListFactory
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly ParameterGenerator $parameterGenerator,
        private readonly PaginationFactoryInterface $paginationFactory,
    ) {
    }

    public function create(ObjectListQueryInterface $objectListQuery): ObjectList
    {
        $contentModel = $this->framework->getAdapter(ContentModel::class)->findById($objectListQuery->getContentModelId());
        $parameters = $this->parameterGenerator->generateFromArray($objectListQuery->getFilterData(), $contentModel);

        $columns = [];
        $values = [];

        foreach ($parameters as $parameter) {
            $columns = array_merge($columns, $parameter->getColumns());
            $values = array_merge($values, $parameter->getValues());
        }

        $pagination = null;

        if ($objectListQuery->getPerPage()) {
            $count = $this->framework->getAdapter(ObjectModel::class)->countPublishedBy($columns, $values);
            $offset = ($objectListQuery->getCurrentPaginationPage() - 1) * $objectListQuery->getPerPage();
            $perPage = $objectListQuery->getPerPage();
            $pagination = $this->paginationFactory->create(new PaginationConfig('page', $count, $perPage)->withCurrentPage($objectListQuery->getCurrentPaginationPage()));
        } else {
            $perPage = $objectListQuery->getMaxNumberOfItems();
            $offset = $objectListQuery->getOffsetItems();
        }

        $order = $objectListQuery->getSort();

        $objects = ObjectModel::findPublishedBy(
            $columns,
            $values,
            [
                'limit' => $perPage,
                'offset' => $offset,
                'order' => $order ?: null,
            ],
        );

        return new ObjectList($objects, $pagination);
    }
}
