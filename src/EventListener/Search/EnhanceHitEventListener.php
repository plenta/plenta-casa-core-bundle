<?php

declare(strict_types=1);

/**
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Search;

use Contao\CoreBundle\Event\BackendSearch\EnhanceHitEvent;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Search\Backend\Hit;
use Contao\StringUtil;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class EnhanceHitEventListener
{
    public function __construct(private readonly Studio $studio)
    {
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

        $x = ObjectModel::findById($row['id']);

        if (null !== $x->singleSRC) {
            $figureBuilder = $this->studio->createFigureBuilder();
            $figureBuilder
                ->fromUuid(StringUtil::binToUuid($x->singleSRC))
            ;

            $newHit = $hit
                ->withContext($this->setContext($row))
                ->withBreadcrumbs($this->setBreadcrumbLabel($hit))
                ->withImageFigureBuilder($figureBuilder)
            ;
        } else {
            $newHit = $hit
                ->withContext($this->setContext($row))
                ->withBreadcrumbs($this->setBreadcrumbLabel($hit))
            ;
        }

        $event->setHit($newHit);
    }

    /**
     * @return array<array{label: string, url: string}>
     */
    protected function setBreadcrumbLabel(Hit $hit): array
    {
        $label = 'Immobilien';

        $breadcrumbs = $hit->getBreadcrumbs();

        if (isset($breadcrumbs[0])) {
            $breadcrumbs[0]['label'] = $label;
        }

        return $breadcrumbs;
    }

    /**
     * @param array<string, mixed>|null $row
     */
    protected function setContext(array|null $row): string
    {
        return \sprintf(
            '%s %s, %s %s',
            $row['street'] ?? '',
            $row['housenumber'] ?? '',
            $row['postal'] ?? '',
            $row['city'] ?? '',
        );
    }
}
