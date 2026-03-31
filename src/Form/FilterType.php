<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Form;

use Plenta\CasaCoreBundle\Events\FilterFormEvent;
use Plenta\CasaCoreBundle\Helper\ObjectTypes;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @phpstan-ignore missingType.generics
 */
class FilterType extends AbstractType
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected RequestStack $requestStack,
        private readonly ObjectTypes $objectTypes,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $model = $options['model'];

        if ($model->plenta_casa_filterAddMarketingType) {
            $builder->add(
                'marketingType',
                ChoiceType::class,
                [
                    'choices' => ['RENT', 'BUY'],
                    'choice_filter' => static fn ($value) => ObjectModel::countPublishedBy(['marketing_type = ?'], [$value]),
                    'choice_label' => static fn ($value, $key, $index) => 'PLENTA.CASA.marketing_types.'.$value,
                    'choice_translation_domain' => 'contao_default',
                    'placeholder' => '-',
                    'priority' => 100,
                    'required' => false,
                    'row_attr' => ['class' => 'widget-select'],
                ],
            );
        }

        if ($model->plenta_casa_filterAddObjectType) {
            $builder->add(
                'objectType',
                ChoiceType::class,
                [
                    'choices' => array_flip($this->objectTypes->getTypes()),
                    'placeholder' => '-',
                    'choice_filter' => fn ($value) => !$value || $this->objectTypes->hasObjects($value),
                    'priority' => 75,
                    'required' => false,
                    'row_attr' => ['class' => 'widget-select'],
                ],
            );
        }

        if ($model->plenta_casa_filterAddKeyword) {
            $builder->add(
                'keyword',
                TextType::class,
                [
                    'priority' => 25,
                    'required' => false,
                    'row_attr' => ['class' => 'widget-text'],
                ],
            );
        }

        if ($model->plenta_casa_filterAddArea) {
            $builder->add(
                'area',
                NumberType::class,
                [
                    'required' => false,
                    'priority' => 10,
                    'row_attr' => ['class' => 'widget-text'],
                ],
            );
        }

        if ($model->plenta_casa_filterAddRooms) {
            $builder->add(
                'rooms',
                ChoiceType::class,
                [
                    'choices' => range(1, 10),
                    'choice_label' => static fn ($value) => $value,
                    'required' => false,
                    'priority' => 5,
                    'row_attr' => ['class' => 'widget-select'],
                ],
            );
        }

        if ($model->plenta_casa_filterAddPrice) {
            $builder->add(
                'price',
                NumberType::class,
                [
                    'required' => false,
                    'priority' => 1,
                    'row_attr' => ['class' => 'widget-text'],
                ],
            );
        }

        $builder
            ->add(
                'submit',
                SubmitType::class,
                [
                    'priority' => 0,
                    'row_attr' => ['class' => 'widget-submit'],
                ],
            )
        ;

        $event = new FilterFormEvent($builder);
        $this->eventDispatcher->dispatch($event, FilterFormEvent::NAME);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'model' => null,
            'label_format' => 'CASA.filter.%name%',
            'translation_domain' => 'plenta_casa',
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
