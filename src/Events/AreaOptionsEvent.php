<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Events;

use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Contracts\EventDispatcher\Event;

class AreaOptionsEvent extends Event
{
    public const NAME = 'plenta_casa.area_options';

    /**
     * @param array<string> $options
     */
    public function __construct(
        protected array $options,
        protected ObjectModel|null $objectModel = null,
    ) {
    }

    /**
     * @return array<string>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array<string> $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public function getObjectModel(): ObjectModel|null
    {
        return $this->objectModel;
    }

    public function setObjectModel(ObjectModel|null $objectModel): void
    {
        $this->objectModel = $objectModel;
    }
}
