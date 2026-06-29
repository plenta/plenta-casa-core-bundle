<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Events;

use Contao\ContentModel;
use Symfony\Component\Form\FormView;
use Symfony\Contracts\EventDispatcher\Event;

class FilterFormBeforeRenderEvent extends Event
{
    public const NAME = 'casa.filter_form_before_render';

    /**
     * @param array<string, mixed> $currentData
     */
    public function __construct(
        protected FormView $formView,
        protected array $currentData,
        protected ContentModel $contentModel,
    ) {
    }

    public function getFormView(): FormView
    {
        return $this->formView;
    }

    public function setFormView(FormView $formView): void
    {
        $this->formView = $formView;
    }

    /**
     * @return array<string, mixed>
     */
    public function getCurrentData(): array
    {
        return $this->currentData;
    }

    /**
     * @param array<string, mixed> $currentData
     */
    public function setCurrentData(array $currentData): void
    {
        $this->currentData = $currentData;
    }

    public function getContentModel(): ContentModel
    {
        return $this->contentModel;
    }

    public function setContentModel(ContentModel $contentModel): void
    {
        $this->contentModel = $contentModel;
    }
}
