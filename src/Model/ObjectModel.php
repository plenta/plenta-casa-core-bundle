<?php

namespace Plenta\CasaCoreBundle\Model;

use Contao\Model;

class ObjectModel extends Model
{
    protected static $strTable = 'tl_pcasa_object';

    public static function findPublishedByIdOrAlias($idOrAlias)
    {
        $columns = ['(tl_pcasa_object.id = ? or alias = ?)'];
        $values = [$idOrAlias, $idOrAlias];

        self::buildPublished($columns, $values);

        return self::findOneBy($columns, $values);
    }

    public function getAddress()
    {
        return ($this->hideStreet ? '' : $this->street.($this->housenumber ? ' '. $this->housenumber : '').', ').$this->postal.' '.$this->city;
    }

    public function getName()
    {
        return $this->title ?: $this->getAddress();
    }

    public static function buildPublished(array &$columns, array &$values)
    {
        $columns = array_merge($columns, [
            'published = ?',
            '(tl_pcasa_object.start = ? OR tl_pcasa_object.start < ?)',
            '(tl_pcasa_object.stop = ? OR tl_pcasa_object.stop > ?)'
        ]);
        $values = array_merge($values, [
            1,
            '', time(),
            '', time(),
        ]);
    }

    public static function findPublishedBy(array $columns, array $values, array $arrOptions = [])
    {
        self::buildPublished($columns, $values);

        return self::findBy($columns, $values, $arrOptions);
    }

    public static function countPublishedBy(array $columns, array $values, array $arrOptions = []): int
    {
        self::buildPublished($columns, $values);

        return self::countBy($columns, $values, $arrOptions);
    }
}