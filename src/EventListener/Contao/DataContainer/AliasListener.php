<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Slug\Slug;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;

class AliasListener
{
    public function __construct(
        private readonly Connection $connection,
        private readonly Slug $slugGenerator,
    ) {
    }

    #[AsCallback(table: 'tl_pcasa_quarter', target: 'fields.alias.save')]
    #[AsCallback(table: 'tl_pcasa_targetgroup', target: 'fields.alias.save')]
    public function aliasSaveCallback(string $value, DataContainer $dc): string
    {
        $aliasExists = function (string $alias) use ($dc): bool {
            $qb = $this->connection->createQueryBuilder();

            $qb->select('1')
                ->from($dc->table)
                ->where('alias = :alias')
                ->andWhere('id <> :id')
                ->setParameter('alias', $alias)
                // ->setParameter('id', (int) $dc->id, ParameterType::INTEGER)
                ->setParameter('id', (int) $dc->id)
                ->setMaxResults(1)
            ;

            return false !== $qb->executeQuery()->fetchOne();
        };

        if (!$value) {
            $value = $this->slugGenerator->generate($dc->activeRecord->title, [], $aliasExists);
        } elseif (preg_match('/^[1-9]\d*$/', $value)) {
            throw new \Exception(\sprintf($GLOBALS['TL_LANG']['ERR']['aliasNumeric'], $value));
        } elseif ($aliasExists($value)) {
            throw new \Exception(\sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $value));
        }

        return $value;
    }
}
