<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class MissionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('dateFin', DateTimeType::class, [
                'widget' => 'single_text',
                'required'=>false,
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'date-timepicker1'],
            ])
            ->add('prixVente')
            ->add('prixAchat')
            ->add('client', EntityType::class, array(
                'class' => 'AppBundle:Client',
                'multiple' => false,
                'label' => 'Client',
                'required'=>false,
                'placeholder' => '--',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            )) ->add('job', EntityType::class, array(
                'class' => 'AppBundle:Job',
                'multiple' => false,
                'label' => 'Motif',
                'required'=>false,
                'placeholder' => '--',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            )) ->add('departement', EntityType::class, array(
                'class' => 'AppBundle:Departement',
                'multiple' => false,
                'required'=>false,
                'placeholder'=>'--',
                'label' => 'Département',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner un département',
                    'multiple' => false

                )
            ))
            ->add('consultant', EntityType::class, array(
                'class' => 'AppBundle:Consultant',
                'multiple' => false,
                'placeholder' => '--',
'required'=>false,
                'label' => 'Consultant',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            ))
            ->add('fournisseur', EntityType::class, array(
                'class' => 'AppBundle:Fournisseur',
                'multiple' => false,
                'label' => 'Fournisseur',
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
                'placeholder'=>'--',
                'label' => 'Bon de commande client',
//                'choice_label' => 'code',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner un',
                    'multiple' => false

                )
            ))
            ->add('bcFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'label' => 'Bon de commande client'
                //   'delete_label' => 'form.label.delete',

            ])
            ->add('contratFFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'label' => 'Contrat fournisseur'
                //   'delete_label' => 'form.label.delete',

            ])
            ->add('contratCFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'label' => 'Contrat Client'
                //   'delete_label' => 'form.label.delete',

            ])
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'Mensuel' => 'mensuel',
                    'Journaliere' => 'journaliere',


                ),
            ))
            ->add('devise', ChoiceType::class, array(
                'choices' => array(
                    'DH' => 'DH',
                    'Euro' => 'euro',
                    'Dollar' => 'dollar',


                ),
            ))
        ;


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Mission'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_mission';
    }


}
