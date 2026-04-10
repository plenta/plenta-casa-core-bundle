<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao;

use Composer\InstalledVersions;
use Contao\BackendUser;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Doctrine\DBAL\Connection;
use Plenta\CasaCoreBundle\Model\LeadModel;
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
        protected readonly Connection $connection,
    ) {
    }

    public function __invoke(): string
    {
        if ($this->auth->isGranted('ROLE_ADMIN')) {
            $GLOBALS['TL_CSS']['plentacasacore_dashboard'] = $this->packages->getUrl('plentacasacore/backend.css', 'plentacasacore');
            /** @var BackendUser $user */
            $user = $this->tokenStorage->getToken()->getUser();

            $data = [
                'salutation' => $this->getRandomSalutation(),
                'username' => $user->name,
                'objectStats' => $this->getObjectStats(),
                'leads' => $this->getAllLeads(),
                'changelog' => 'https://plentacasa.com/changelog',
                'manufacturer' => $this->translator->trans('PLENTA.CASA.manufacturer', [], 'plenta_casa_dashboard'),
                'version' => $this->translator->trans(
                    'PLENTA.CASA.version',
                    ['%s' => InstalledVersions::getVersion('plenta/plenta-casa-core-bundle')],
                    'plenta_casa_dashboard',
                ),
            ];

            return
                $this->twig->render(
                    '@Contao/Backend/plenta_casa_dashboard.html.twig',
                    $data,
                );
        }

        return '';
    }

    /**
     * @return array<string, int>
     */
    public function getObjectStats(): array
    {
        $sql = '
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN published = 1 THEN 1 ELSE 0 END) AS published
            FROM tl_pcasa_object
        ';

        $result = $this->connection->executeQuery($sql)->fetchAssociative();

        return [
            'total' => (int) $result['total'],
            'published' => (int) $result['published'],
        ];
    }

    private function getRandomSalutation(): string
    {
        $raw = $this->translator->trans('PLENTA.CASA.greeting.random_choice', [], 'plenta_casa_dashboard');

        $choices = array_values(array_filter(array_map(trim(...), explode('|', $raw))));

        return $choices[random_int(0, \count($choices) - 1)];
    }

    private function getAllLeads(): int
    {
        $leads = LeadModel::countAll() ?? 0;

        return $leads;
    }
}
