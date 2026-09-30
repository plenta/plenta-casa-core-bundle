<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Form;

use Contao\StringUtil;
use Plenta\CasaCoreBundle\Events\FilterFormEvent;
use Plenta\CasaCoreBundle\Filter\ParameterGenerator;
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
use Symfonycasts\DynamicForms\DynamicFormBuilder;

/**
 * @phpstan-ignore missingType.generics
 */
class FilterType extends AbstractType
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected RequestStack $requestStack,
        private readonly ObjectTypes $objectTypes,
        private readonly ParameterGenerator $parameterGenerator,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $model = $options['model'];

        $builder = new DynamicFormBuilder($builder);
        $elements = StringUtil::deserialize($model->plenta_casa_filterElements, true);

        $modelParameters = $this->parameterGenerator->generateFromModel($model);
        $columns = [];
        $values = [];

        foreach ($modelParameters as $parameter) {
            $columns = array_merge($columns, $parameter->getColumns());
            $values = array_merge($values, $parameter->getValues());
        }

        if (\in_array('marketingType', $elements, true)) {
            $builder->add(
                'marketingType',
                ChoiceType::class,
                [
                    'choices' => ['RENT', 'BUY'],
                    'label' => 'PLENTA.CASA.filter.marketingType',
                    'translation_domain' => 'contao_default',
                    'choice_filter' => static fn ($value) => ObjectModel::countPublishedBy(array_merge($columns, ['marketing_type = ?']), array_merge($values, [$value])),
                    'choice_label' => static fn ($value, $key, $index) => 'PLENTA.CASA.marketing_types.'.$value,
                    'choice_translation_domain' => 'contao_default',
                    'placeholder' => '-',
                    'priority' => $this->getPriority($elements, 'marketingType'),
                    'required' => false,
                    'row_attr' => ['class' => 'widget-checkbox'],
                    'attr' => ['class' => 'checkbox-group'],
                ],
            );
        }

        if (\in_array('objectType', $elements, true)) {
            $builder->add(
                'objectType',
                ChoiceType::class,
                [
                    'choices' => array_flip($this->objectTypes->getTypes()),
                    'placeholder' => '-',
                    'choice_filter' => fn ($value) => !$value || $this->objectTypes->hasObjects((int) $value, $columns, $values),
                    'priority' => $this->getPriority($elements, 'objectType'),
                    'required' => false,
                    'row_attr' => ['class' => 'widget-select'],
                ],
            );
        }

        if (\in_array('keyword', $elements, true)) {
            $builder->add(
                'keyword',
                TextType::class,
                [
                    'priority' => $this->getPriority($elements, 'keyword'),
                    'required' => false,
                    'row_attr' => ['class' => 'widget-text keyword'],
                    'attr' => ['placeholder' => 'CASA.filter.keywordPlaceholder'],
                ],
            );
        }

        if (\in_array('area', $elements, true)) {
            $builder->add(
                'area',
                NumberType::class,
                [
                    'required' => false,
                    'priority' => $this->getPriority($elements, 'area'),
                    'row_attr' => ['class' => 'widget-text'],
                ],
            );
        }

        if (\in_array('rooms', $elements, true)) {
            $builder->add(
                'rooms',
                ChoiceType::class,
                [
                    'choices' => range(1, 10),
                    'choice_label' => static fn ($value) => $value,
                    'required' => false,
                    'priority' => $this->getPriority($elements, 'rooms'),
                    'row_attr' => ['class' => 'widget-select'],
                ],
            );
        }

        if (\in_array('price', $elements, true)) {
            $builder->add(
                'price',
                NumberType::class,
                [
                    'required' => false,
                    'priority' => $this->getPriority($elements, 'price'),
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

    /**
     * @param array<string> $elements
     */
    public function getPriority(array $elements, string $key, int $step = 5): int
    {
        $max = \count($elements) + 1;

        return ($max - array_search($key, $elements, true)) * $step;
    }
}
