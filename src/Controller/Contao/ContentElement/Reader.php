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
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;
use Contao\CoreBundle\Routing\ResponseContext\ResponseContextAccessor;
use Contao\Input;
use Contao\StringUtil;
use Contao\System;
use Contao\Template;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\CasaCoreBundle\Schema\RealEstateSchema;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsContentElement(type: 'plenta_casa_reader', category: 'plentacasa')]
class Reader extends AbstractContentElementController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly ResponseContextAccessor $responseContextAccessor,
        private readonly RealEstateSchema $realEstateSchema,
    ) {
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
            $objTemplate = new BackendTemplate('be_wildcard');
            $parts = StringUtil::deserialize($model->plenta_casa_readerParts);
            $objTemplate->wildcard = $this->translator->trans('CTE.plenta_casa_reader.0', [], 'contao_default').': '.implode(', ', array_column($parts, 'plenta_casa_readerPartType'));

            return $objTemplate->getResponse();
        }

        $alias = Input::get('auto_item');

        if (!$object = ObjectModel::findPublishedByIdOrAlias($alias)) {
            throw new PageNotFoundException();
        }

        $template->object = $object;

        $this->getHtmlHeadBag()->setTitle($object->getName());

        $responseContext = $this->responseContextAccessor->getResponseContext();

        if ($responseContext && $responseContext->has(HtmlHeadBag::class)) {
            $htmlHeadBag = $responseContext->get(HtmlHeadBag::class);
            $htmlDecoder = System::getContainer()->get('contao.string.html_decoder');

            if ($object->metaTitle) {
                $htmlHeadBag->setTitle($htmlDecoder->inputEncodedToPlainText($object->metaTitle));
            } elseif ($object->title) {
                $htmlHeadBag->setTitle($htmlDecoder->inputEncodedToPlainText($object->title));
            }

            if ($object->metaDescription) {
                $htmlHeadBag->setMetaDescription($htmlDecoder->inputEncodedToPlainText($object->metaDescription));
            } elseif ($object->description) {
                $htmlHeadBag->setMetaDescription($htmlDecoder->htmlToPlainText($object->description));
            }

            if ($object->robots) {
                $htmlHeadBag->setMetaRobots($object->robots);
            }
        }

        $template->realEstateSchema = $this->realEstateSchema->getStructuredData($object);

        return $template->getResponse();
    }
}
