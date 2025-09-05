<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Doctrine\DBAL\Connection;
use Contao\CoreBundle\Slug\Slug;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Exception;

class AliasListener
{
    public function __construct(
        private readonly Connection $connection,
        private readonly Slug $slugGenerator
    )
    {
    }

    #[AsCallback(table: 'tl_pcasa_quarter', target: 'fields.alias.save')]
    #[AsCallback(table: 'tl_pcasa_targetgroup', target: 'fields.alias.save')]
    public function aliasSaveCallback($value, DataContainer $dc): string
    {
        $aliasExists = function (string $alias) use ($dc): bool {
            $qb = $this->connection->createQueryBuilder();

            $qb->select('1')
                ->from($this->connection->quoteIdentifier($dc->table))
                ->where('alias = :alias')
                ->andWhere('id <> :id')
                ->setParameter('alias', $alias)
                //->setParameter('id', (int) $dc->id, ParameterType::INTEGER)
                ->setParameter('id', (int) $dc->id)
                ->setMaxResults(1);

            return $qb->executeQuery()->fetchOne() !== false;
        };

        if (!$value) {
            $value = $this->slugGenerator->generate($dc->activeRecord->title, [], $aliasExists);
        } elseif (preg_match('/^[1-9]\d*$/', $value)) {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasNumeric'], $value));
        } elseif ($aliasExists($value)) {
            throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $value));
        }

        return $value;
    }
}
