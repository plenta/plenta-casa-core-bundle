<?php

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Contao\DataContainer;
use Plenta\CasaCoreBundle\Helper\AddressHelper;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\GeocodingBundle\Classes\GeoCoordinatesHelper;
use Plenta\GeocodingBundle\Classes\QueryObject;
use Twig\Environment as TwigEnvironment;
use Symfony\Component\HttpFoundation\RequestStack;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

class TlPcasaObjectListener
{
    public function __construct(
        protected RequestStack $requestStack,
        protected TwigEnvironment $twig,
        protected AddressHelper $addressHelper,
        protected GeoCoordinatesHelper $coordinatesHelper,
    )
    {
    }

    #[AsCallback(table: 'tl_pcasa_object', target: 'config.onsubmit')]
    public function submitCallback(DataContainer $dc)
    {
        if ($dc->activeRecord->street && $dc->activeRecord->postal && $dc->activeRecord->city) {
            $queryObject = (new QueryObject())
                ->setStreet($dc->activeRecord->street.($dc->activeRecord->housenumber ? (' '.$dc->activeRecord->housenumber) : ''))
                ->setPostal($dc->activeRecord->postal)
                ->setCity($dc->activeRecord->city)
                ->setCountryCode($dc->activeRecord->country)
                ->setAddressDescriptor(true)
            ;

            $coordinates = $this->coordinatesHelper->getCoordinates($queryObject);

            if ($coordinates) {
                $object = ObjectModel::findByPk($dc->activeRecord->id);
                $object->district = $coordinates->getSublocality();
                $object->coordinates_lat = $coordinates->getLat();
                $object->coordinates_lng = $coordinates->getLng();
                $object->save();
            }
        }
    }
}
