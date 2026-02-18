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
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Contao\CoreBundle\Image\Studio\Studio;

#[AsEventListener]
class EnhanceHitEventListener
{
    public function __construct(
        private readonly Connection $connection,
        private readonly Studio $studio,
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

        $x = ObjectModel::findByPk($row['id']);

        if (null !== $x->singleSRC) {
            $figureBuilder = $this->studio->createFigureBuilder();

            $figureBuilder
                ->fromUuid(StringUtil::binToUuid($x->singleSRC))
            ;

            $newHit = $hit
                ->withContext($this->setContect($row))
                ->withBreadcrumbs($this->setBreadcrumbLabel($hit))
                ->withImageFigureBuilder($figureBuilder)
            ;
        } else {
            $newHit = $hit
                ->withContext($this->setContect($row))
                ->withBreadcrumbs($this->setBreadcrumbLabel($hit))
            ;
        }

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
