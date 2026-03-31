<?php

declare(strict_types=1);

namespace Plenta\CasaCoreBundle\Model;

use Contao\Model;
use Contao\Model\Collection;

class ObjectModel extends Model
{
    protected static $strTable = 'tl_pcasa_object';

    public static function findPublishedByIdOrAlias(int|string $idOrAlias): self|null
    {
        $columns = ['(tl_pcasa_object.id = ? or alias = ?)'];
        $values = [$idOrAlias, $idOrAlias];

        self::buildPublished($columns, $values);

        return self::findOneBy($columns, $values);
    }

    public function getAddress(): string
    {
        return ($this->hideStreet ? '' : $this->street.($this->housenumber ? ' '.$this->housenumber : '').', ').$this->postal.' '.$this->city;
    }

    public function getName(): string
    {
        return $this->title ?: $this->getAddress();
    }

    /**
     * @param array<string> $columns
     * @param array<mixed>  $values
     */
    public static function buildPublished(array &$columns, array &$values): void
    {
        $columns = array_merge($columns, [
            'published = ?',
            '(tl_pcasa_object.start = ? OR tl_pcasa_object.start < ?)',
            '(tl_pcasa_object.stop = ? OR tl_pcasa_object.stop > ?)',
        ]);
        $values = array_merge($values, [
            1,
            '', time(),
            '', time(),
        ]);
    }

    /**
     * @param  array<string>                $columns
     * @param  array<mixed>                 $values
     * @param  array<string, mixed>         $arrOptions
     * @return Collection<ObjectModel>|null
     */
    public static function findPublishedBy(array $columns, array $values, array $arrOptions = []): Collection|null
    {
        self::buildPublished($columns, $values);

        return self::findBy($columns, $values, $arrOptions);
    }

    /**
     * @param array<string>        $columns
     * @param array<mixed>         $values
     * @param array<string, mixed> $arrOptions
     */
    public static function countPublishedBy(array $columns, array $values, array $arrOptions = []): int
    {
        self::buildPublished($columns, $values);

        return self::countBy($columns, $values, $arrOptions);
    }
}
