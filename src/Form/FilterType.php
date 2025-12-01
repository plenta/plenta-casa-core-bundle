<?php

namespace Plenta\CasaCoreBundle\Form;

use Plenta\CasaCoreBundle\Events\FilterFormEvent;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\CasaCoreBundle\Model\ObjectTypeModel;
use Plenta\CasaCoreBundle\Model\TypeModel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected RequestStack $requestStack,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $model = $options['model'];

        if ($model->plenta_casa_filterAddMarketingType) {
            $builder->add('marketingType', ChoiceType::class, [
                'choices' => ['RENT', 'BUY'],
                'choice_filter' => fn ($value) => ObjectModel::countPublishedBy(['marketing_type = ?'], [$value]),
                'choice_label' => fn ($value, $key, $index) => 'PLENTA.CASA.marketing_types.'.$value,
                'choice_translation_domain' => 'contao_default',
                'placeholder' => '-',
                'priority' => 100,
                'required' => false,
            ]);
        }

        if ($model->plenta_casa_filterAddObjectType) {
            $builder->add('objectType', ChoiceType::class, [
                'choices' => ObjectTypeModel::findAll(),
                'choice_value' => 'id',
                'choice_label' => 'title',
                'placeholder' => '-',
                'choice_filter' => fn ($value) => $value ? ObjectModel::countPublishedBy(['object_type = ?'], [$value->id]) : true,
                'priority' => 75,
                'required' => false,
                'attr' => ['data-action' => 'change->casa-filter#types']
            ]);
        }

        $objectTypeFromRequest = $this->requestStack->getCurrentRequest()->query->all('filter')['objectType'] ?? null;

        if ($model->plenta_casa_filterAddType) {
            $builder->add('type', ChoiceType::class, [
                'choices' => $model->plenta_casa_filterAddObjectType ? ($objectTypeFromRequest ? TypeModel::findBy('pid', is_array($objectTypeFromRequest) ? $objectTypeFromRequest[0] : $objectTypeFromRequest) : []) : TypeModel::findAll(),
                'choice_value' => 'id',
                'choice_label' => 'title',
                'choice_filter' => fn ($value) => $value ? ObjectModel::countPublishedBy(['type = ?'], [$value->id]) : true,
                'placeholder' => '-',
                'priority' => 50,
                'required' => false,
                'attr' => ['data-casa-filter-target' => 'type']
            ]);
        }

        if ($model->plenta_casa_filterAddKeyword) {
            $builder->add('keyword', TextType::class, [
                'priority' => 25,
                'required' => false,
            ]);
        }

        if ($model->plenta_casa_filterAddArea) {
            $builder->add('area', NumberType::class, [
                'required' => false,
                'priority' => 10,
            ]);
        }

        if ($model->plenta_casa_filterAddRooms) {
            $builder->add('rooms', ChoiceType::class, [
                'choices' => range(1, 10),
                'choice_label' => fn ($value) => $value,
                'required' => false,
                'priority' => 5,
            ]);
        }

        if ($model->plenta_casa_filterAddPrice) {
            $builder->add('price', NumberType::class, [
                'required' => false,
                'priority' => 1,
            ]);
        }

        $builder
            ->add('submit', SubmitType::class, [
                'priority' => 0,
            ])
        ;

        $event = new FilterFormEvent($builder);
        $this->eventDispatcher->dispatch($event, FilterFormEvent::NAME);
    }

    public function configureOptions(OptionsResolver $resolver)
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