<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao\Backend;

use Contao\CoreBundle\Event\ContaoCoreEvents;
use Contao\CoreBundle\Event\MenuEvent;
use Plenta\CasaCoreBundle\Controller\Contao\Backend\AddonsController;
use Plenta\CasaCoreBundle\Controller\Contao\Backend\ConfigController;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsEventListener(ContaoCoreEvents::BACKEND_MENU_BUILD, priority: -255)]
class BackendMenuListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function __invoke(MenuEvent $event): void
    {
        $factory = $event->getFactory();
        $tree = $event->getTree();

        if ('mainMenu' !== $tree->getName()) {
            return;
        }

        $contentNode = $tree->getChild('plenta_casa');

        $node = $factory
            ->createItem('plentacasa_addons', ['route' => AddonsController::class])
            ->setLabel($this->translator->trans('MOD.plentacasa_addons.1', [], 'contao_modules'))
            ->setLinkAttribute('title', $this->translator->trans('MOD.plentacasa_addons.1', [], 'contao_modules'))
            ->setLinkAttribute('class', 'plentacasa_addons')
            ->setLinkAttribute('data-turbo', 'true')
            ->setCurrent(AddonsController::class === $this->requestStack->getCurrentRequest()->get('_controller'))
        ;

        $contentNode->addChild($node);

        $node = $factory
            ->createItem('plentacasa_config', ['route' => ConfigController::class])
            ->setLabel($this->translator->trans('MOD.plentacasa_config.1', [], 'contao_modules'))
            ->setLinkAttribute('title', $this->translator->trans('MOD.plentacasa_config.1', [], 'contao_modules'))
            ->setLinkAttribute('class', 'plentacasa_config')
            ->setLinkAttribute('data-turbo', 'true')
            ->setCurrent(ConfigController::class === $this->requestStack->getCurrentRequest()->get('_controller'))
        ;

        $contentNode->addChild($node);
    }
}
