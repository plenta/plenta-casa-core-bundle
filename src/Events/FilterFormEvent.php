<?php

namespace Plenta\CasaCoreBundle\Events;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\EventDispatcher\Event;

class FilterFormEvent extends Event
{
    public const NAME = 'plenta_casa.filter.form';

    public function __construct(
        protected FormBuilderInterface $formBuilder,
    ) {

    }

    public function getFormBuilder(): FormBuilderInterface
    {
        return $this->formBuilder;
    }

    public function setFormBuilder(FormBuilderInterface $formBuilder): void
    {
        $this->formBuilder = $formBuilder;
    }
}