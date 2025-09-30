<?php

namespace Plenta\CasaCoreBundle\Helper;

use Contao\CoreBundle\Slug\Slug;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AliasGenerator
{
    public function __construct(
        #[Autowire(param: 'plenta_casa_core.alias_generation')]
        protected array $aliasGeneration,
        protected Slug $slugGenerator,
    ) {
    }

    public function generateAlias(array $object)
    {
        $aliasParts = [];

        foreach ($this->aliasGeneration['parts'] as $field) {
            if (!empty($object[$field])) {
                $aliasParts[] = $object[$field];
            }
        }

        return $this->slugGenerator->generate(implode(' ', $aliasParts), [
            'locale' => $this->aliasGeneration['locale'],
            'validChars' => $this->aliasGeneration['validChars'],
        ], fn ($alias) => ObjectModel::findBy(['tl_pcasa_object.id != ?', 'alias = ?'], [$alias, $object['id']]) !== null);
    }
}