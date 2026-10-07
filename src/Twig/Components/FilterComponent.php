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
use Contao\CoreBundle\Controller\AbstractController;
use Plenta\CasaCoreBundle\Events\FilterFormBeforeRenderEvent;
use Plenta\CasaCoreBundle\Form\FilterType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\PreReRender;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('casa_filter_component')]
class FilterComponent extends AbstractController
{
    use ComponentToolsTrait;
    use ComponentWithFormTrait {
        getFormView as traitGetFormView;
    }
    use DefaultActionTrait;

    #[LiveProp]
    public int $modelId;

    /**
     * @var array<string, mixed>
     */
    #[LiveProp(writable: true, fieldName: 'getFormName()', url: true)]
    public array $formValues = [];

    /**
     * @var array<string, mixed>
     */
    #[LiveProp]
    public array $initialValues = [];

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function mount(int $modelId): void
    {
        $this->modelId = $modelId;
    }

    #[PreReRender(priority: -100)]
    public function preRender(): void
    {
        $this->formValues = array_filter($this->formValues, static fn ($key) => '__dynamic_error' !== $key, ARRAY_FILTER_USE_KEY);
        $this->emit('casa:filter-changed', ['data' => $this->formValues]);
    }

    public function getFormView(): FormView
    {
        $view = $this->traitGetFormView();

        $event = new FilterFormBeforeRenderEvent($view, $this->formValues, ContentModel::findByIdOrAlias($this->modelId));
        $this->eventDispatcher->dispatch($event, FilterFormBeforeRenderEvent::NAME);

        return $view;
    }

    /**
     * @return FormInterface<FilterType>
     */
    protected function instantiateForm(): FormInterface
    {
        $form = $this->createForm(
            FilterType::class,
            $this->initialValues,
            [
                'model' => ContentModel::findById($this->modelId),
            ],
        );

        if (empty($this->initialValues) && ($request = $this->requestStack->getCurrentRequest())->query->has('filter')) {
            $form->handleRequest($request);
        }

        return $form;
    }
}
