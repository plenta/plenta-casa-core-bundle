<?php

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Contao\DataContainer;
use Plenta\CasaCoreBundle\Helper\AddressHelper;
use Twig\Environment as TwigEnvironment;
use Symfony\Component\HttpFoundation\RequestStack;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

class TlPcasaObjectListener
{
    public function __construct(
        protected RequestStack $requestStack,
        protected TwigEnvironment $twig,
        protected AddressHelper $addressHelper,
    )
    {
    }

    #[AsCallback(table: 'tl_pcasa_object', target: 'list.label.label')]
    public function onLabelCallback(array $row, string $label, DataContainer $dc, array $labels): string
    {
        $row['city_name'] = $this->addressHelper->getCityDistrictLabel($row['city'], $row['district']) ?? null;

        dump($row);
        //dump($label);
        //dump($labels);
        //dump($dc);

        return $this->twig->render('@Contao/Backend/plenta_casa_object_list_item.html.twig', $row);
    }
}
