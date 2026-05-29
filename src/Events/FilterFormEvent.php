<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Events;

use Plenta\CasaCoreBundle\Form\FilterType;
use Symfony\Contracts\EventDispatcher\Event;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class FilterFormEvent extends Event
{
    public const NAME = 'plenta_casa.filter.form';

    /**
     * @param DynamicFormBuilder<FilterType> $formBuilder
     */
    public function __construct(protected DynamicFormBuilder $formBuilder)
    {
    }

    /**
     * @return DynamicFormBuilder<FilterType>
     */
    public function getFormBuilder(): DynamicFormBuilder
    {
        return $this->formBuilder;
    }

    /**
     * @param DynamicFormBuilder<FilterType> $formBuilder
     */
    public function setFormBuilder(DynamicFormBuilder $formBuilder): void
    {
        $this->formBuilder = $formBuilder;
    }
}
