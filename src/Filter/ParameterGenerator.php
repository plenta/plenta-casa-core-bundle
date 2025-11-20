<?php

namespace Plenta\CasaCoreBundle\Filter;

use Contao\ContentModel;
use Contao\StringUtil;
use Plenta\CasaCoreBundle\Events\KeywordsEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ParameterGenerator
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function generateFromRequest(Request $request, ContentModel $model): array
    {
        $parameters = [];

        $keywordEvent = new KeywordsEvent(contentModel:  $model);
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

            if ($filter['area'] ?? null) {
                if ($min = (int) ($filter['area']['min'] ?? null)) {
                    $parameters[] = new Parameter(
                        'areaMin',
                        ['(cast(livingArea as unsigned) >= ? OR cast(floorArea as unsigned) >= ?)'],
                        [$min, $min],
                    );
                }

                if ($max = (int) ($filter['area']['max'] ?? null)) {
                    $parameters[] = new Parameter(
                        'areaMax',
                        ['(cast(livingArea as unsigned) <= ? OR cast(floorArea as unsigned) <= ?)'],
                        [$max, $max],
                    );
                }
            }

            if ($filter['rooms'] ?? null) {
                if ($min = (int) ($filter['rooms']['min'] ?? null)) {
                    $parameters[] = new Parameter(
                        'roomsMin',
                        ['cast(rooms as unsigned) >= ?'],
                        [$min],
                    );
                }

                if ($max = (int) ($filter['rooms']['max'] ?? null)) {
                    $parameters[] = new Parameter(
                        'roomsMax',
                        ['cast(rooms as unsigned) <= ?'],
                        [$max],
                    );
                }
            }

            if ($filter['price'] ?? null) {
                if ($min = (int) ($filter['price']['min'] ?? null)) {
                    $parameters[] = new Parameter(
                        'priceMin',
                        ['((price != ? and price >= ?) OR (baseRent != ? and baseRent >= ?))'],
                        [0, $min, 0, $min],
                    );
                }

                if ($max = (int) ($filter['price']['max'] ?? null)) {
                    $parameters[] = new Parameter(
                        'priceMax',
                        ['((price != ? and price <= ?) OR (baseRent != ? and baseRent <= ?))'],
                        [0, $max, 0, $max],
                    );
                }
            }
        }

        return $parameters;
    }
}