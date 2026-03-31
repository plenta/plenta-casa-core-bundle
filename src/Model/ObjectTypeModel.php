<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Model;

use Contao\Model;

class ObjectTypeModel extends Model
{
    protected static $strTable = 'tl_pcasa_object_type';

    public function getTemplate(): string|null
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
