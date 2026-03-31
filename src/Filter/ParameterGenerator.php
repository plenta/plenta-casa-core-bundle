<?php

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
        protected EventDispatcherInterface $eventDispatcher, private readonly ObjectTypes $objectTypes,
    ) {
    }

    public function generateFromRequest(Request $request, ?ContentModel $model): array
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
                $type = is_array($filter['objectType']) ? $filter['objectType'][0] : $filter['objectType'];

                $children = array_keys($this->objectTypes->getTypes($type));
                $children[] = $type;
                $criteria = [];
                $values = [];

                foreach ($children as $child) {
                    $criteria[] = 'object_type = ?';
                    $values[] = $child;
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

            if ($area = ((int) ($filter['area'] ?? null))) {
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
                    ['((price != ? and price <= ?) OR (baseRent != ? and baseRent <= ?))'],
                    [0, $price, 0, $price],
                );
            }
        }

        $event = new FilterParameterEvent($model, $parameters);
        $this->eventDispatcher->dispatch($event, $event::NAME);

        return $event->getParameters();
    }
}