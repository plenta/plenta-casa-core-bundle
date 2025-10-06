<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao;

use Composer\InstalledVersions;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment as TwigEnvironment;

#[AsHook('getSystemMessages')]
class GetSystemMessagesListener
{
    public function __construct(
        protected readonly TwigEnvironment $twig,
        protected readonly TokenStorageInterface $tokenStorage,
        private readonly TranslatorInterface $translator,
        protected readonly AuthorizationCheckerInterface $auth,
        protected Packages $packages,
    ) {
    }

    public function __invoke(): string
    {
        if ($this->auth->isGranted('ROLE_ADMIN')) {
            $GLOBALS['TL_CSS']['plentacasacore_dashboard'] = $this->packages->getUrl('plentacasacore/backend.css', 'plentacasacore');

            $data = [
                'salutation' => $this->getRandomSalutation(),
                'username' => $this->tokenStorage->getToken()->getUser()->name,
                'changelog' => 'https://plentacasa.com/changelog',
                'manufacturer' => $this->translator->trans('PLENTA.CASA.manufacturer', [], 'plenta_casa_dashboard'),
                'version' => $this->translator->trans(
                    'PLENTA.CASA.version',
                    ['%s' => InstalledVersions::getVersion('plenta/plenta-casa-core-bundle')],
                    'plenta_casa_dashboard'
                ),
            ];

            return
                $this->twig->render(
                    '@Contao/Backend/plenta_casa_dashboard.html.twig',
                    $data
                );
        }

        return '';
    }

    private function getRandomSalutation(): string
    {
        $raw = $this->translator->trans('PLENTA.CASA.greeting.random_choice', [], 'plenta_casa_dashboard');

        $choices = array_values(array_filter(array_map('trim', explode('|', $raw))));

        return $choices[random_int(0, \count($choices) - 1)];
    }
}
