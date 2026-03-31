<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Helper;

use Contao\CoreBundle\Slug\Slug;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AliasGenerator
{
    /**
     * @param array<string, array<string>> $aliasGeneration
     */
    public function __construct(
        #[Autowire(param: 'plenta_casa_core.alias_generation')]
        protected array $aliasGeneration,
        protected Slug $slugGenerator,
    ) {
    }

    /**
     * @param array<string, mixed> $object
     */
    public function generateAlias(array $object): string
    {
        $aliasParts = [];

        foreach ($this->aliasGeneration['parts'] as $field) {
            if (!empty($object[$field])) {
                $aliasParts[] = $object[$field];
            }
        }

        return $this->slugGenerator->generate(
            implode(' ', $aliasParts),
            [
                'locale' => $this->aliasGeneration['locale'],
                'validChars' => $this->aliasGeneration['validChars'],
            ],
            static fn ($alias) => null !== ObjectModel::findBy(['tl_pcasa_object.id != ?', 'alias = ?'], [$alias, $object['id']]),
        );
    }
}
