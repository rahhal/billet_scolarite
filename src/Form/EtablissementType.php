<?php

namespace App\Form;

use App\Entity\Etablissement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EtablissementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ministere')
            ->add('mandoubia')
            ->add('code')
            ->add('nom')
            ->add('ville')
            ->add('adresse',TextType::class, [
                'required'=> false,
                 ])
            ->add('tel',TextType::class, [
                'required'=> false,
                 ])
            ->add('fax',TextType::class, [
                'required'=> false,
                 ])
           // ->add('cle_license')
           // ->add('nbr_attestation_presence')
            ->add("gouvernorat", null, [
                "attr" => [
                    "class" => "select-search"
                ]
            ])
           // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Etablissement::class,
        ]);
    }
}
