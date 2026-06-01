<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Schema;

use Contao\CoreBundle\Asset\ContaoContext;
use Contao\Environment;
use Contao\FilesModel;
use Contao\StringUtil;
use Plenta\CasaCoreBundle\Events\SchemaOrgEvent;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class RealEstateSchema
{
    public function __construct(
        #[Autowire(service: 'contao.assets.files_context')]
        private readonly ContaoContext $contaoFileContext,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function getStructuredData(?ObjectModel $object): array
    {
        if (null === $object) {
            return [];
        }

        $data = [];
        $data['@context'] = 'https://schema.org';
        $data['@type'] = 'RealEstateListing';
        $data['name'] = strip_tags(StringUtil::restoreBasicEntities($object->title));

        if ($object->description) {
            $data['description'] = $this->sanitizeDescription($object->description);
        }

        if (!$object->hideStreet) {
            $data['address'] = $this->generateAddress($object);
        }

        if ($object->livingArea) {
            $data['floorSize'] = [
                '@type' => 'QuantitativeValue',
                'value' => (float) $object->livingArea,
                'unitCode' => 'MTK',
            ];
        }

        if ($object->rooms) {
            $data['numberOfRooms'] = (int) $object->rooms;
        }

        $data = $this->generateOffers($object, $data);
        $data = $this->generateImage($object, $data);

        $event = new SchemaOrgEvent($object, $data);
        $this->eventDispatcher->dispatch($event, SchemaOrgEvent::NAME);

        return $event->getData();
    }

    private function generateAddress(ObjectModel $object): array
    {
        $address = [
            '@type' => 'PostalAddress',
            'addressCountry' => $object->country ?: 'DE',
        ];

        if ($object->street) {
            $address['streetAddress'] = $object->street.($object->housenumber ? ' '.$object->housenumber : '');
        }

        if ($object->postal) {
            $address['postalCode'] = $object->postal;
        }

        if ($object->city) {
            $address['addressLocality'] = $object->city;
        }

        if ($object->district) {
            $address['addressRegion'] = $object->district;
        }

        return $address;
    }

    private function generateOffers(ObjectModel $object, array $data): array
    {
        return match ($object->marketing_type) {
            'BUY' => $this->generateBuyOffer($object, $data),
            'RENT' => $this->generateRentOffer($object, $data),
            default => $data,
        };
    }

    private function generateBuyOffer(ObjectModel $object, array $data): array
    {
        if ($object->priceOnRequest || $object->priceNegotiable) {
            return $data;
        }

        if ($object->price) {
            $data['offers'] = [
                '@type' => 'Offer',
                'price' => (float) $object->price,
                'priceCurrency' => 'EUR',
                'availability' => 'https://schema.org/InStock',
            ];
        }

        return $data;
    }

    private function generateRentOffer(ObjectModel $object, array $data): array
    {
        $price = $object->totalRent ?: $object->baseRent;

        if ($price) {
            $data['offers'] = [
                '@type' => 'Offer',
                'price' => (float) $price,
                'priceCurrency' => 'EUR',
                'priceSpecification' => [
                    '@type' => 'UnitPriceSpecification',
                    'price' => (float) $price,
                    'priceCurrency' => 'EUR',
                    'unitText' => 'month',
                ],
            ];
        }

        return $data;
    }

    private function generateImage(ObjectModel $object, array $data): array
    {
        if (!$object->singleSRC) {
            return $data;
        }

        $file = FilesModel::findByUuid(StringUtil::binToUuid($object->singleSRC));

        if (null === $file) {
            return $data;
        }

        $staticUrl = $this->contaoFileContext->getStaticUrl();

        $data['image'] = [
            '@type' => 'ImageObject',
            'url' => rtrim(Environment::get('url').'/'.$staticUrl, '/').'/'.$file->path,
        ];

        return $data;
    }

    private function sanitizeDescription(?string $description): ?string
    {
        if (null === $description) {
            return null;
        }

        $description = strip_tags($description, '<br><p><ol><ul><li><h1><h2><h3><h4><h5><h6><strong><em>');
        $description = StringUtil::stripInsertTags($description);

        return StringUtil::restoreBasicEntities($description);
    }
}
