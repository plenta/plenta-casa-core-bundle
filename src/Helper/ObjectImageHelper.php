<?php

declare(strict_types=1);

/**
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Helper;

use Contao\StringUtil;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\CasaCoreBundle\Model\ObjectTypeModel;

class ObjectImageHelper
{
    public function getImage(ObjectModel $object): string|null
    {
        if ($object->singleSRC) {
            return $object->singleSRC;
        }

        if ($object->multiSRC) {
            $images = StringUtil::deserialize($object->multiSRC, true);
            if (!empty($images)) {
                return $images[0];
            }
        }

        $type = ObjectTypeModel::findById($object->object_type);

        return $this->getTypeImage($type);
    }

    private function getTypeImage(ObjectTypeModel|null $type): string|null
    {
        if (null === $type) {
            return null;
        }

        if ($type->singleSRC) {
            return $type->singleSRC;
        }

        if ($type->pid) {
            return $this->getTypeImage(ObjectTypeModel::findById($type->pid));
        }

        return null;
    }
}
