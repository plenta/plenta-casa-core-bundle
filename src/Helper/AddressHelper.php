<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Helper;

use Doctrine\DBAL\Connection;

class AddressHelper
{
    public function __construct(protected readonly Connection $connection)
    {
    }

    public function getCityDistrictLabel(?int $cityId, ?int $districtId): ?string
    {
        // Fall 1: District angegeben -> hole Parent (Stadt) + Child (District)
        if ($districtId) {
            $qb = $this->connection->createQueryBuilder();
            $qb->select("CONCAT(parent.title, ', ', child.title) AS label")
                ->from('tl_pcasa_city', 'child')
                ->innerJoin('child', 'tl_pcasa_city', 'parent', 'parent.id = child.pid')
                ->where('child.id = :districtId')
                ->setParameter('districtId', $districtId);

            // Optional absichern, dass der District auch wirklich zur gewünschten Stadt gehört
            if ($cityId) {
                $qb->andWhere('parent.id = :cityId')
                    ->setParameter('cityId', $cityId);
            }

            $label = $qb->fetchOne();
            if ($label !== false) {
                return (string) $label;
            }

            // Fallback: wenn District nicht existiert/zu keiner Stadt passt, versuche Stadt allein
            if ($cityId) {
                return $this->getCityOnlyLabel($cityId);
            }

            return null;
        }

        // Fall 2: nur Stadt angegeben
        if ($cityId) {
            return $this->getCityOnlyLabel($cityId);
        }

        // Nichts angegeben
        return null;
    }

    private function getCityOnlyLabel(int $cityId): ?string
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('title')
            ->from('tl_pcasa_city', 'c')
            ->where('c.id = :cityId')
            ->setParameter('cityId', $cityId);

        $title = $qb->fetchOne();

        return $title === false ? null : (string) $title;
    }
}