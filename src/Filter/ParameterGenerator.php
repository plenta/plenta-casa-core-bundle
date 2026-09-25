<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Filter;

use Contao\ContentModel;
use Contao\StringUtil;
use Plenta\CasaCoreBundle\Events\FilterParameterEvent;
use Plenta\CasaCoreBundle\Events\KeywordsEvent;
use Plenta\CasaCoreBundle\Helper\ObjectTypes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ParameterGenerator
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        private readonly ObjectTypes $objectTypes,
    ) {
    }

    /**
     * @param  array<string, mixed> $filter
     * @return array<Parameter>
     */
    public function generateFromArray(array $filter, ContentModel|null $model, bool $multipleObjectTypes = false): array
    {
        $parameters = [];

        $keywordEvent = new KeywordsEvent(contentModel: $model);
        $this->eventDispatcher->dispatch($keywordEvent, $keywordEvent::NAME);

        if ($filter['marketingType'] ?? null) {
            $parameters[] = new Parameter(
                'marketingType',
                ['marketing_type = ?'],
                [$filter['marketingType']],
            );
        }

        if ($filter['objectType'] ?? null) {
            if ($multipleObjectTypes) {
                $types = \is_array($filter['objectType']) ? $filter['objectType'] : [$filter['objectType']];
            } else {
                $types = \is_array($filter['objectType']) ? [$filter['objectType'][0]] : [$filter['objectType']];
            }

            $criteria = [];
            $values = [];

            foreach ($types as $type) {
                $children = array_keys($this->objectTypes->getTypes((int) $type));
                $children[] = $type;

                foreach ($children as $child) {
                    $criteria[] = 'object_type = ?';
                    $values[] = $child;
                }
            }

            $parameters[] = new Parameter(
                'objectType',
                ['('.implode(' OR ', $criteria).')'],
                $values,
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

            if ('AND' === $keywordEvent->getConnector()) {
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

        if ($area = (int) ($filter['area'] ?? null)) {
            $parameters[] = new Parameter(
                'areaMin',
                ['(cast(livingArea as unsigned) >= ? OR cast(floorArea as unsigned) >= ?)'],
                [$area, $area],
            );
        }

        if ($rooms = (int) ($filter['rooms'] ?? null)) {
            $parameters[] = new Parameter(
                'roomsMin',
                ['cast(rooms as unsigned) >= ?'],
                [$rooms],
            );
        }

        if ($price = (int) ($filter['price'] ?? null)) {
            $parameters[] = new Parameter(
                'priceMax',
                ['((price != ? and price <= ?) OR (baseRent != ? and baseRent <= ?) OR priceOnRequest = ? OR priceNegotiable = ?)'],
                [0, $price, 0, $price, 1, 1],
            );
        }

        if ($city = ($filter['city'] ?? null)) {
            if (!\is_array($city)) {
                $city = [$city];
            }

            $criteria = [];

            foreach ($city as $ignored) {
                $criteria[] = 'city = ?';
            }

            $parameters[] = new Parameter(
                'city',
                ['('.implode(' OR ', $criteria).')'],
                $city,
            );
        }

        if ($filter['district'] ?? null) {
            $criteria = [];

            foreach ($filter['district'] as $ignored) {
                $criteria[] = 'district = ?';
            }

            $parameters[] = new Parameter(
                'district',
                ['('.implode(' OR ', $criteria).')'],
                $filter['district'],
            );
        }

        if ($filter['quarter'] ?? null) {
            $criteria = [];

            foreach ($filter['quarter'] as $ignored) {
                $criteria[] = 'quarter = ?';
            }

            $parameters[] = new Parameter(
                'quarter',
                ['('.implode(' OR ', $criteria).')'],
                $filter['quarter'],
            );
        }

        if ($filter['targetgroup'] ?? null) {
            $criteria = [];
            $values = [];

            foreach ($filter['targetgroup'] as $targetgroup) {
                $criteria[] = 'targetgroups LIKE ?';
                $values[] = '%"'.$targetgroup.'"%';
            }

            $parameters[] = new Parameter(
                'targetgroup',
                ['('.implode(' OR ', $criteria).')'],
                $values,
            );
        }

        if ($filter['roomsExact'] ?? null) {
            $parameters[] = new Parameter(
                'roomsExact',
                ['rooms = ?'],
                [(string) $filter['roomsExact']],
            );
        }

        $event = new FilterParameterEvent($model, $parameters, $filter);
        $this->eventDispatcher->dispatch($event, $event::NAME);

        return $event->getParameters();
    }

    /**
     * @return array<Parameter>
     */
    public function generateFromRequest(Request $request, ContentModel|null $model): array
    {
        return $this->generateFromArray($request->query->all('filter'), $model);
    }

    /**
     * @return array<Parameter>
     */
    public function generateFromModel(ContentModel $contentModel): array
    {
        $data = [];

        if ($contentModel->plenta_casa_preFilter) {
            if ($contentModel->plenta_casa_preFilterType) {
                $data['objectType'] = StringUtil::deserialize($contentModel->plenta_casa_preFilterType);
            }

            if ($contentModel->plenta_casa_preFilterLocation) {
                $data['city'] = StringUtil::deserialize($contentModel->plenta_casa_preFilterLocation);
            }

            if ($contentModel->plenta_casa_preFilterDistrict) {
                $data['district'] = StringUtil::deserialize($contentModel->plenta_casa_preFilterDistrict);
            }

            if ($contentModel->plenta_casa_preFilterQuarter) {
                $data['quarter'] = StringUtil::deserialize($contentModel->plenta_casa_preFilterQuarter);
            }

            if ($contentModel->plenta_casa_preFilterTargetGroup) {
                $data['targetgroup'] = StringUtil::deserialize($contentModel->plenta_casa_preFilterTargetGroup);
            }

            if ($contentModel->plenta_casa_preFilterRooms) {
                $data['roomsExact'] = (int) $contentModel->plenta_casa_preFilterRooms;
            }
        }

        $parameters = [];

        if (!empty($data)) {
            $parameters = $this->generateFromArray($data, $contentModel, true);
        }

        return $parameters;
    }
}
