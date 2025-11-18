<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Search;

use Contao\CoreBundle\Event\BackendSearch\EnhanceHitEvent;
use Contao\CoreBundle\Search\Backend\Hit;
use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class EnhanceHitEventListener
{
    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    public function __invoke(EnhanceHitEvent $event): void
    {
        $hit = $event->getHit();

        if (null === $hit) {
            return;
        }

        $metadata = $hit->getDocument()->getMetadata();

        if (($metadata['table'] ?? null) !== 'tl_pcasa_object') {
            return;
        }

        $row = $metadata['row'] ?? null;

        $newHit = $hit
            ->withContext($this->setContect($row))
            ->withBreadcrumbs($this->setBreadcrumbLabel($hit))
        ;

        // NEW
        /*
        $newHit = new Hit($hit->getDocument(), 'Immobilie', $hit->getEditUrl());
        $newHit->withContext($this->setContect($row));
        $newHit->withBreadcrumbs($this->setBreadcrumbLabel($hit));
        */

        $event->setHit($newHit);
    }

    protected function setBreadcrumbLabel(Hit $hit): array
    {
        $label = 'Immobilien';

        $breadcrumbs = $hit->getBreadcrumbs() ?? [];

        if (isset($breadcrumbs[0])) {
            $breadcrumbs[0]['label'] = $label;
        }

        return $breadcrumbs;
    }

    protected function setContect(?array $row): string
    {
        if (!$row || !isset($row['id'])) {
        }

        $titleParts = [];

        if (!empty($row['title'])) {
            $titleParts[] = $row['title'];
        }

        if (!empty($row['city'])) {
            $titleParts[] = $row['city'];
        }

        $context = \sprintf(
            '%s %s, %s %s',
            $row['street'] ?? '',
            $row['housenumber'] ?? '',
            $row['postal'] ?? '',
            $row['city'] ?? ''
        );

        return $context;
    }
}
