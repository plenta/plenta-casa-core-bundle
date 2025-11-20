<?php

namespace Plenta\CasaCoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class MinMaxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('min', NumberType::class, [
                'required' => $options['required'],
            ])
            ->add('max', NumberType::class, [
                'required' => $options['required'],
            ])
        ;
    }
}