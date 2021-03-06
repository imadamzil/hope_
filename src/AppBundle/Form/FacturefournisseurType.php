<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
class FacturefournisseurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('achatHT')->add('nbjours')->add('mois')->add('year')->add('achatTTC')->add('taxe')->add('factureFournisseur')->add('etat')->add('date')
            ->add('documentFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
                'label' => 'Facture Fournisseur'
                //   'delete_label' => 'form.label.delete',

            ])->add('updatedAt')->add('createdAt')->add('fournisseur')->add('mission');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Facturefournisseur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_facturefournisseur';
    }


}
