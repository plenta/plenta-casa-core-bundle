<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

class FilterElementMigration extends AbstractMigration
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['tl_content'])) {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_content');

        $options = $GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_filterElements']['options'];

        if (!\array_key_exists('plenta_casa_filterelements', $columns)) {
            return false;
        }

        $params = [];

        foreach ($options as $option) {
            $params[] = 'c.plenta_casa_filterAdd'.ucfirst((string) $option).' = :true';

            if (!\array_key_exists('plenta_casa_filteradd'.strtolower((string) $option), $columns)) {
                return false;
            }
        }

        $qb = $this->connection->createQueryBuilder();

        return (bool) $qb->select('true')
            ->from('tl_content', 'c')
            ->where($qb->expr()->isNull('c.plenta_casa_filterElements'))
            ->andWhere('c.type = :filter')
            ->andWhere($qb->expr()->or(...$params))
            ->setParameter('true', true)
            ->setParameter('filter', 'plenta_casa_filter')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne()
        ;
    }

    public function run(): MigrationResult
    {
        $options = $GLOBALS['TL_DCA']['tl_content']['fields']['plenta_casa_filterElements']['options'];

        $params = [];

        foreach ($options as $option) {
            $params[] = 'c.plenta_casa_filterAdd'.ucfirst((string) $option).' = :true';
        }

        $qb = $this->connection->createQueryBuilder();

        $filters = $qb->select('*')
            ->from('tl_content', 'c')
            ->where($qb->expr()->isNull('c.plenta_casa_filterElements'))
            ->andWhere('c.type = :filter')
            ->andWhere($qb->expr()->or(...$params))
            ->setParameter('true', true)
            ->setParameter('filter', 'plenta_casa_filter')
            ->executeQuery()
            ->fetchAllAssociative()
        ;

        foreach ($filters as $filter) {
            $elements = [];

            foreach ($options as $option) {
                if ($filter['plenta_casa_filterAdd'.ucfirst((string) $option)]) {
                    $elements[] = $option;
                }
            }

            $this->connection->update('tl_content', ['plenta_casa_filterElements' => serialize($elements)], ['id' => $filter['id']]);
        }

        return $this->createResult(true);
    }
}
