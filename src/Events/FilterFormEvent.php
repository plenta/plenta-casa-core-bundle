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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\EventDispatcher\Event;

class FilterFormEvent extends Event
{
    public const NAME = 'plenta_casa.filter.form';

    /**
     * @param FormBuilderInterface<FilterType> $formBuilder
     */
    public function __construct(
        protected FormBuilderInterface $formBuilder,
    ) {
    }

    /**
     * @return FormBuilderInterface<FilterType>
     */
    public function getFormBuilder(): FormBuilderInterface
    {
        return $this->formBuilder;
    }

    /**
     * @param FormBuilderInterface<FilterType> $formBuilder
     */
    public function setFormBuilder(FormBuilderInterface $formBuilder): void
    {
        $this->formBuilder = $formBuilder;
    }
}
