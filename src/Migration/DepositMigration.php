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
use Doctrine\DBAL\Types\DecimalType;

class DepositMigration extends AbstractMigration
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['tl_pcasa_object'])) {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_pcasa_object');

        if (!\array_key_exists('deposit', $columns)) {
            return false;
        }

        if ($columns['deposit']->getType() instanceof DecimalType) {
            return false;
        }

        return 0 !== $this->connection->fetchOne(
            "SELECT COUNT(*) FROM tl_pcasa_object WHERE deposit = '' OR deposit IS NULL OR deposit NOT REGEXP '^[0-9]+(\\.[0-9]+)?$'",
        );
    }

    public function run(): MigrationResult
    {
        $this->connection->executeStatement(
            "UPDATE tl_pcasa_object SET deposit = '0' WHERE deposit = '' OR deposit IS NULL OR deposit NOT REGEXP '^[0-9]+(\\.[0-9]+)?$'",
        );

        return $this->createResult(true);
    }
}
