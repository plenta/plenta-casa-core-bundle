<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

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
