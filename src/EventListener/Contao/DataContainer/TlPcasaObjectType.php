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
use Contao\CoreBundle\Twig\Finder\FinderFactory;
use Contao\DC_Table;

class TlPcasaObjectType
{
    public function __construct(protected FinderFactory $finderFactory)
    {
    }

    /**
     * @return array<string>
     */
    #[AsCallback(table: 'tl_pcasa_object_type', target: 'fields.customTpl.options')]
    public function onCustomTplOptions(DC_Table $dc): array
    {
        return $this->finderFactory
            ->create()
            ->identifier('partials/plenta_casa_list_object')
            ->extension('html.twig')
            ->withVariants()
            ->asTemplateOptions()
        ;
    }
}
