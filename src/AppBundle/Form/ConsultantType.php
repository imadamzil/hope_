<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class ConsultantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')

            ->add('type', ChoiceType::class, array(
                'choices' => array(

                    'Externe' => 'externe',
                    'Interne' => 'interne',


                ),
            ))
            ->add('echeance', EntityType::class, array(
                'class' => 'AppBundle:Echeance',
                'multiple' => false,
                'label' => 'Echeance',
                'required'=>false,
                'placeholder' => '--',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            ))
            ->add('marge')
            ->add('anciennte')

            ->add('autoVirement', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('natureMission', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('salaire')
            ->add('rib')
            ->add('cin')
            ->add('tjm')
            ->add('cvFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'label' => 'CV'
                //   'delete_label' => 'form.label.delete',

            ])
            ->add('tel')
            ->add('email')
            ->add('adresse');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Consultant'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_consultant';
    }


}
