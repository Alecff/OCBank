<?php

namespace App\Form;

use App\Entity\Result;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $url = $options['data']['url'];
        $builder
            ->add('CPU', TextType::class, ['label' => 'CPU name',
                'attr' => [
                    'class'=> 'js-cpu-class',
                    'data-autocomplete-url' => $url
                ]])
            ->add('MaxSpeed', NumberType::class, ['label' => 'Max Speed (GHz)'])
            ->add('MaxSpeedVoltage', NumberType::class, ['label' => 'Voltage Required'])
            ->add('nodes', CollectionType::class, [
                'entry_type' => NodeFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
                ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
