<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Controller\Contao\ContentElement;

/*
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
use Plenta\CasaCoreBundle\Form\FilterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsContentElement(type: 'plenta_casa_filter', category: 'plentacasa')]
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
            $objTemplate->wildcard = $this->translator->trans('CTE.plenta_casa_filter.0', [], 'contao_default');

            return $objTemplate->getResponse();
        }

        $filter = $this->createForm(
            FilterType::class,
            null,
            [
                'model' => $model,
            ],
        );

        $filter->handleRequest($request);

        $template->form = $filter->createView();

        return $template->getResponse();
    }
}
