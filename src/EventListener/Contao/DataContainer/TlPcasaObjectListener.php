<?php

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Contao\DataContainer;
use Plenta\CasaCoreBundle\Model\ObjectTypeModel;
use Plenta\CasaCoreBundle\Model\TypeModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

class TlPcasaObjectListener
{
    #[AsCallback(table: 'tl_pcasa_object', target: 'fields.type.options')]
    public function typeOptionsCallback(DataContainer $dc)
    {
        $typesArr = [];

        $objectType = ObjectTypeModel::findByPk($dc->activeRecord?->object_type);

        if ($objectType) {
            $types = TypeModel::findByPid($objectType->id);

            if ($types) {
                foreach ($types as $type) {
                    $typesArr[$type->id] = $type->title;
                }
            }
        }

        return $typesArr;
    }
}
