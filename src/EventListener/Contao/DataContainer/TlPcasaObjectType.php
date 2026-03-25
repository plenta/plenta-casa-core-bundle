<?php

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Twig\Finder\FinderFactory;
use Contao\DC_Table;

class TlPcasaObjectType
{
    public function __construct(
        protected FinderFactory $finderFactory,
    ) {
    }

    #[AsCallback(table: 'tl_pcasa_object_type', target: 'fields.customTpl.options')]
    public function onCustomTplOptions(DC_Table $dc)
    {
        return $this->finderFactory
            ->create()
            ->identifier('partials/_plenta_casa_list_object')
            ->extension('html.twig')
            ->withVariants()
            ->asTemplateOptions()
            ;
    }
}