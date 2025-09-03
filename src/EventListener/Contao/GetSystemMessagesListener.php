<?php

namespace Plenta\CasaCoreBundle\EventListener\Contao;

use Twig\Environment as TwigEnvironment;
use Symfony\Contracts\Translation\TranslatorInterface;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsHook('getSystemMessages')]
class GetSystemMessagesListener
{
    public function __construct(
        protected readonly TwigEnvironment $twig,
        protected readonly TokenStorageInterface $tokenStorage,
        private readonly TranslatorInterface $translator,
        protected readonly AuthorizationCheckerInterface $auth
    ) {
    }

    public function __invoke(): string
    {
        if ($this->auth->isGranted('ROLE_ADMIN')) {
            $GLOBALS['TL_CSS']['plentacasacore'] = 'bundles/plentacasacore/dashboard.css';

            $data['salutation'] = $this->getRandomSalutation();
            $data['username'] = $this->tokenStorage->getToken()->getUser()->name;

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
        $raw = $this->translator->trans('plenta_casa.greeting.random_choice', [], 'contao_defaut');

        $choices = array_values(array_filter(array_map('trim', explode('|', $raw))));

        return $choices[random_int(0, \count($choices) - 1)];
    }
}
