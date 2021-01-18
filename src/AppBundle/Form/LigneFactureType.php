<?php

namespace AppBundle\Form;

use AppBundle\Entity\Projetconsultant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneFactureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('numero')
//            ->add('mois')
//            ->add('year')
//            ->add('totalHt')
//            ->add('totalTTC')
//            ->add('facture')
            ->add('projetconsultant', Projetconsultant2Type::class, [
                'data_class' => Projetconsultant::class,
                'label'=>'Ligne_facture'
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
