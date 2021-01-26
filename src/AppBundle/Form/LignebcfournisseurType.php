<?php

namespace AppBundle\Form;

use AppBundle\Entity\Projetconsultant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class LignebcfournisseurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('numero')
//            ->add('mois', ChoiceType::class, array(
//                'attr' => ['class' => 'form-control'],
//                'required' => true,
//                'choices' => array(
//                    'Janvier' => 1,
//                    'Fevrier' => 2,
//                    'Mars' => 3,
//                    'Avril' => 4,
//                    'Mai' => 5,
//                    'Juin' => 6,
//                    'Juillet' => 7,
//                    'Aout' => 8,
//                    'Septembre' => 9,
//                    'Octobre' => 10,
//                    'Novembre' => 11,
//                    'Décembre' => 12,
//
//
//                ),
//            ))
//           ->add('year')
//            ->add('totalHt')
//            ->add('totalTTC')
//            ->add('facture')
            ->add('projetconsultant', Projetconsultant3Type::class, [
                'data_class' => Projetconsultant::class,
                'label'=>'Bc fournisseur'
            ])
            ->add('nbjour', NumberType::class, array(
                'required'=>false,
                'label' => 'Nb jours',
                'attr' => array(
                    'class' => 'nbjour',
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\LigneFacture'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_lignefacture';
    }


}
