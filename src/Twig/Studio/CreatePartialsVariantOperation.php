<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Twig\Studio;

use Contao\CoreBundle\DependencyInjection\Attribute\AsOperationForTemplateStudioElement;
use Contao\CoreBundle\Twig\Studio\Operation\AbstractCreateVariantOperation;

#[AsOperationForTemplateStudioElement]
class CreatePartialsVariantOperation extends AbstractCreateVariantOperation
{
    protected function getPrefix(): string
    {
        return 'partials';
    }
}
