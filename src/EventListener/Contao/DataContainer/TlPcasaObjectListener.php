<?php

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Contao\DataContainer;
use Contao\DC_Table;
use Plenta\CasaCoreBundle\Helper\AliasGenerator;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\CasaCoreBundle\Model\ObjectTypeModel;
use Plenta\CasaCoreBundle\Model\TypeModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

class TlPcasaObjectListener
{
    public function __construct(
        protected AliasGenerator $aliasGenerator,
    ) {
    }

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

    #[AsCallback(table: 'tl_pcasa_object', target: 'fields.alias.save')]
    public function aliasSaveCallback($varValue, DC_Table $dc)
    {
        if (!empty($varValue)) {
            if (preg_match('/^[1-9]\d*$/', $varValue)) {
                throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasNumeric'], $varValue));
            } elseif (ObjectModel::findBy(['tl_pcasa_object.id != ?', 'alias = ?'], [$dc->id, $varValue]) !== null) {
                throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
            }

            return $varValue;
        }

        return $this->aliasGenerator->generateAlias($dc->getActiveRecord());
    }
}
