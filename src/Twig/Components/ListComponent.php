<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Twig\Components;

use Contao\ContentModel;
use Contao\CoreBundle\Pagination\PaginationInterface;
use Contao\Model\Collection;
use Contao\PageModel;
use Plenta\CasaCoreBundle\Helper\Sorting;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\CasaCoreBundle\ObjectList\ObjectListFactory;
use Plenta\CasaCoreBundle\ObjectList\ObjectListQueryFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\PreReRender;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('casa_list_component')]
class ListComponent
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $modelId;

    /**
     * @var array<string, mixed>
     */
    #[LiveProp]
    public array $filterData;

    #[LiveProp(url: true)]
    public int $page = 1;

    #[LiveProp(writable: true, url: true)]
    public string $sort = '';

    public int $pages = 0;

    /**
     * @var Collection<ObjectModel>|null
     */
    public Collection|null $objects = null;

    public PageModel|null $jumpTo = null;

    public ContentModel|null $model = null;

    public PaginationInterface|null $pagination = null;

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly ObjectListFactory $objectListFactory,
        private readonly ObjectListQueryFactory $objectListQueryFactory,
        private readonly Sorting $sorting,
    ) {
    }

    public function mount(int $modelId): void
    {
        $this->modelId = $modelId;
        $this->filterData = $this->requestStack->getCurrentRequest()->query->all('filter');
        $this->page = (int) $this->requestStack->getCurrentRequest()->query->get('page', 1);
        $this->sort = $this->requestStack->getCurrentRequest()->query->get('sort', (string) ContentModel::findById($this->modelId)->plenta_casa_sorting);

        $this->build();
    }

    #[PreReRender]
    public function build(): void
    {
        $this->model = ContentModel::findById($this->modelId);

        $query = $this->objectListQueryFactory->create(
            $this->modelId,
            $this->filterData,
            $this->page,
            $this->model->numberOfItems,
            null,
            $this->getListSorting($this->sort),
        );

        $objectList = $this->objectListFactory->create($query);

        $this->pagination = $objectList->getPagination();
        $this->objects = $objectList->getObjects();

        $this->jumpTo = PageModel::findByIdOrAlias($this->model->jumpTo);
    }

    /**
     * @param array<string, mixed> $data
     */
    #[LiveListener('casa:filter-changed')]
    public function setData(#[LiveArg] array $data): void
    {
        $this->filterData = $data;
        $this->page = 1;
    }

    public function getListSorting(string|null $sorting): string
    {
        return $this->sorting->getListSorting($sorting, $this->model);
    }

    #[LiveAction]
    public function setPage(#[LiveArg] int $page): void
    {
        $this->page = $page;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }
}
