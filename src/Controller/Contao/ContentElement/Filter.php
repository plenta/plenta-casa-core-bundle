<?php

declare(strict_types=1);

namespace Plenta\CasaCoreBundle\Controller\Contao\ContentElement;

/**
 * @package       Customer
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

use Contao\BackendTemplate;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\System;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsContentElement(category: 'plentacasa', template: 'content_element/content-plentacasa-filter', type: 'plenta_casa_filter')]
class Filter extends AbstractContentElementController
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
            $objTemplate = new BackendTemplate('be_wildcard');
            $objTemplate->wildcard = $this->translator->trans('CTE.plenta_casa_filter.0', [], 'contao_defaut');

            return $objTemplate->getResponse();
        }

        return $template->getResponse();
    }
}
