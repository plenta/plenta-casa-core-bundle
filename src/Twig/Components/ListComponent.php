<?php

declare(strict_types=1);

namespace Plenta\CasaCoreBundle\Twig\Components;

use Contao\ContentModel;
use Contao\CoreBundle\Pagination\PaginationConfig;
use Contao\CoreBundle\Pagination\PaginationFactoryInterface;
use Contao\CoreBundle\Pagination\PaginationInterface;
use Contao\Model\Collection;
use Contao\PageModel;
use Plenta\CasaCoreBundle\Events\ListOrderEvent;
use Plenta\CasaCoreBundle\Filter\ParameterGenerator;
use Plenta\CasaCoreBundle\Model\ObjectModel;
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
        private readonly ParameterGenerator $parameterGenerator,
        private readonly PaginationFactoryInterface $paginationFactory,
        private readonly EventDispatcherInterface $eventDispatcher,
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
        $parameters = $this->parameterGenerator->generateFromArray($this->filterData, $this->model);

        $columns = [];
        $values = [];

        foreach ($parameters as $parameter) {
            $columns = array_merge($columns, $parameter->getColumns());
            $values = array_merge($values, $parameter->getValues());
        }

        if (true === (bool) $this->model->plenta_casa_featured) {
            $columns[] = 'tl_pcasa_object.featured = ?';
            $values[] = true;
        }

        if ($this->model->perPage) {
            $count = ObjectModel::countPublishedBy($columns, $values);
            $offset = ($this->page - 1) * $this->model->perPage;
            $perPage = $this->model->perPage;
            $this->pagination = $this->paginationFactory->create(new PaginationConfig('page', $count, $perPage)->withCurrentPage($this->page));
        } else {
            $perPage = $this->model->numberOfItems ?: null;
            $offset = null;
        }

        $order = $this->getListSorting($this->sort);

        $this->objects = ObjectModel::findPublishedBy(
            $columns,
            $values,
            [
                'limit' => $perPage,
                'offset' => $offset,
                'order' => $order ?: null,
            ],
        );

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
        $order = match ($sorting) {
            'order_headline_asc' => 'tl_pcasa_object.title',
            'order_headline_desc' => 'tl_pcasa_object.title DESC',
            'order_random' => 'RAND()',
            'order_date_asc' => 'tl_pcasa_object.tstamp',
            'order_date_desc' => 'tl_pcasa_object.tstamp DESC',
            default => '',
        };

        $event = new ListOrderEvent($sorting, $order, $this->model);
        $this->eventDispatcher->dispatch($event, $event::NAME);

        return $event->getOrder();
    }

    #[LiveAction]
    public function setPage(#[LiveArg] int $page): void
    {
        $this->page = $page;
    }
}
