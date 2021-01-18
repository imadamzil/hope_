<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Projetconsultant2Type extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('titre')
            ->add('job', EntityType::class, array(
                'class' => 'AppBundle:Job',
                'multiple' => false,
                'required'=>false,
                'label' => 'Mission motif',
                'attr' => array(
                    'class' => 'chosen-select3',
                    'data-placeholder' => 'Selectionner',
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
                    'class' => 'chosen-select3',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            ))

            ->add('vente');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Projetconsultant'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_projetconsultant';
    }


}
