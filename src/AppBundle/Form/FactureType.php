<?php

namespace AppBundle\Form;

use AppBundle\Entity\FactureHsup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('etat', ChoiceType::class, array(

                'choices' => array(
                    'Payé' => 'payé',
                    'Payé avec devise' => 'payé avec devise',
                    'Non Payé' => 'non payé',


                ),
            ))
            ->add('year')
            ->add('nbjour')
            ->add('mois', ChoiceType::class, array(
                'placeholder' => 'Choose an option',
                'choices' => array(
                    '--'=>null,
                    'Janvier' => 1,
                    'Fevrier' => 2,
                    'Mars' => 3,
                    'Avril' => 4,
                    'Mai' => 5,
                    'Juin' => 6,
                    'Juillet' => 7,
                    'Aout' => 8,
                    'Septembre' => 9,
                    'Octobre' => 10,
                    'Novembre' => 11,
                    'Décembre' => 12,


                ),
            ))
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'placeholder' => 'Date Facture',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'date-timepicker1'],
            ])
            ->add('client', EntityType::class, array(
                'class' => 'AppBundle:Client',
                'multiple' => false,
                'label' => 'Client',
                'placeholder' => '--',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            ))
            ->add('consultant', EntityType::class, array(
                'class' => 'AppBundle:Consultant',
                'multiple' => false,
                'label' => 'Consultant',
                'placeholder' => '--',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            ))
            ->add('bcclient', EntityType::class, array(
                'class' => 'AppBundle:Bcclient',
                'multiple' => false,
                'placeholder' => '--',
                'label' => 'Bon de commande client',
//                'choice_label' => 'code',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            ))
            ->add('mission', EntityType::class, array(
                'class' => 'AppBundle:Mission',
                'multiple' => false,
                'label' => 'Mission',
//                'choice_label' => 'code',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            ))
            ->add('comptebancaire', EntityType::class, array(
                'class' => 'AppBundle:Comptebancaire',
                'multiple' => false,
                'label' => 'Compte bancaire',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            ))
            ->add('facturehsups', CollectionType::class, [
                'entry_type' => FactureHsupType::class,
                'entry_options' => ['label' => false],
                'attr' => array(
                    'class' => 'my-selectors inl ',
                    'label' => 'consultants list :',
                ),
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,

                'by_reference' => false,

            ])

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Facture'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_facture';
    }


}
