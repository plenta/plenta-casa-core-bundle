<?php

namespace Plenta\CasaCoreBundle\Model;

use Contao\Model;

class ObjectTypeModel extends Model
{
    protected static $strTable = 'tl_pcasa_object_type';

    public function getTemplate()
    {
        if ($this->customTpl) {
            return $this->customTpl;
        }

        if ($this->pid) {
            return self::findById($this->pid)->getTemplate();
        }

        return null;
    }
}