<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Controller\Contao\ContentElement;

use Contao\BackendTemplate;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\System;
use Contao\Template;
use Plenta\CasaCoreBundle\Filter\ParameterGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsContentElement(type: 'plenta_casa_listing', category: 'plentacasa')]
class Listing extends AbstractContentElementController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        protected EventDispatcherInterface $eventDispatcher,
        protected ParameterGenerator $parameterGenerator,
    ) {
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
            $objTemplate = new BackendTemplate('be_wildcard');
            $objTemplate->wildcard = $this->translator->trans('CTE.plenta_casa_listing.0', [], 'contao_default');

            return $objTemplate->getResponse();
        }

        return $template->getResponse();
    }
}
