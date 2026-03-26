<?php

declare(strict_types=1);

namespace Plenta\CasaCoreBundle\Twig\Studio;

use Contao\CoreBundle\DependencyInjection\Attribute\AsOperationForTemplateStudioElement;
use Contao\CoreBundle\Twig\Studio\Operation\AbstractCreateVariantOperation;

#[AsOperationForTemplateStudioElement]
class CreateCasaVariantOperation extends AbstractCreateVariantOperation
{
    protected function getPrefix(): string
    {
        return 'casa/reader/parts';
    }
}
