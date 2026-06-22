<?php

declare(strict_types=1);

namespace Plenta\CasaCoreBundle\ObjectList;

use Contao\CoreBundle\Pagination\PaginationInterface;
use Contao\Model\Collection;
use Plenta\CasaCoreBundle\Model\ObjectModel;

final readonly class ObjectList
{
    /**
     * @param Collection<ObjectModel>|null $objects
     */
    public function __construct(
        private Collection|null $objects,
        private PaginationInterface|null $pagination,
    ) {
    }

    /**
     * @return Collection<ObjectModel>|null
     */
    public function getObjects(): Collection|null
    {
        return $this->objects;
    }

    public function getPagination(): PaginationInterface|null
    {
        return $this->pagination;
    }
}
