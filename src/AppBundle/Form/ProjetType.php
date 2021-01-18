<?php

namespace AppBundle\Form;

use AppBundle\Entity\Projetconsultant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('statut')
            ->add('dateDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'placeholder' => 'Date Début mission',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'required'=>false,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'date-timepicker1'],
            ])
            ->add('dateFin', DateTimeType::class, [
                'widget' => 'single_text',
                'placeholder' => 'Date Début mission',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'required'=>false,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'date-timepicker1'],
            ])

            ->add('client', EntityType::class, array(
                'class' => 'AppBundle:Client',
                'multiple' => false,
                'label' => 'Client',
                'required'=>false,

                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            ))
            ->add('projetconsultants', CollectionType::class, [
                'entry_type' => ProjetconsultantType::class,
                'entry_options' => ['label' => false],
                'attr' => array(
                    'class' => 'my-selector inl ',
                    'label'=>'consultants list :',
                ),
                'prototype' => true,
                'allow_add' => true,
                'allow_delete'=>true,

                'by_reference' => false,

            ])->add('submit',SubmitType::class)

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Projet'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_projet';
    }


}
