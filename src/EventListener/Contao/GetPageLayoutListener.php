<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\PageRegular;
use Symfony\Component\Asset\Packages;

#[AsHook('getPageLayout')]
class GetPageLayoutListener
{
    public function __construct(protected Packages $packages)
    {
    }

    public function __invoke(PageModel $page, LayoutModel $layout, PageRegular $pageRegular): void
    {
        if (!$layout->addPlentaCasaCss) {
            return;
        }

        $GLOBALS['TL_CSS']['plentacasa_theme'] = $this->packages->getUrl(
            'plentacasacore/theme.css',
            'plentacasacore'
        );
    }
}
