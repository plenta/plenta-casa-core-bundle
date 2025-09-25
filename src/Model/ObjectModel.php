<?php

namespace Plenta\CasaCoreBundle\Model;

use Contao\Model;

class ObjectModel extends Model
{
    protected static $strTable = 'tl_pcasa_object';

    public static function findPublishedByIdOrAlias($idOrAlias)
    {
        return self::findOneBy([
            '(tl_pcasa_object.id = ? or alias = ?)',
            'published = ?',
            '(tl_pcasa_object.start = ? OR tl_pcasa_object.start < ?)',
            '(tl_pcasa_object.stop = ? OR tl_pcasa_object.stop > ?)'
        ], [
            $idOrAlias, $idOrAlias,
            1,
            '', time(),
            '', time(),

        ]);
    }

    public function getAddress()
    {
        return $this->street.($this->housenumber ? ' '. $this->housenumber : '').', '.$this->postal.' '.$this->city;
    }

    public function getTitle()
    {
        return $this->title ?: $this->getAddress();
    }
}