<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VirementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'mm-yyyy',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'date-timepicker1'],
            ])
            ->add('code')
            ->add('etat', ChoiceType::class, array(
                'choices' => array(
                    'Executé' => 'executé',
                    'En attente' => 'en attente',
                    'Validé' => 'validé',


                ),
                'required'=>false,
            ))
            ->add('achat')
            ->add('consultant', EntityType::class, array(
                'class' => 'AppBundle:Consultant',
                'multiple' => false,
                'label' => 'Consultant',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            ))
            ->add('bcfournisseur', EntityType::class, array(
                'class' => 'AppBundle:Bcfournisseur',
                'multiple' => false,
                'label' => 'Bon de commande fournisseur',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            ))

        /*    ->add('facturefournisseur', EntityType::class, [
                'class' => 'AppBundle:Facturefournisseur',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.etat = :uid')


                        ->setParameter('uid', 'non payé')
                        ->orderBy('u.date', 'ASC');
                },
                'multiple' => false,
                'label' => 'Facture fournisseur',
                'attr' => array(
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Selectionner',
                    'multiple' => false

                )
            ])*/
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Virement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_virement';
    }


}
