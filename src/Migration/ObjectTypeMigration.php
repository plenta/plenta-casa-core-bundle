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

class ObjectTypeMigration extends AbstractMigration
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['tl_pcasa_object_type', 'tl_pcasa_type'])) {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_pcasa_object_type');

        if (!\array_key_exists('pid', $columns)) {
            return false;
        }

        return 0 !== $this->connection->fetchOne('SELECT COUNT(*) FROM tl_pcasa_type');
    }

    public function run(): MigrationResult
    {
        $types = $this->connection->fetchAllAssociative('SELECT * FROM tl_pcasa_type');

        foreach ($types as $type) {
            $typeId = $type['id'];
            unset($type['id']);
            $this->connection->insert('tl_pcasa_object_type', $type);
            $id = $this->connection->lastInsertId();

            $this->connection->update('tl_pcasa_object', ['object_type' => $id], ['type' => $typeId]);
            $this->connection->delete('tl_pcasa_type', ['id' => $typeId]);
        }

        return $this->createResult(true);
    }
}
