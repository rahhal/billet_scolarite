<?php

namespace App\Form\Search;

use App\Entity\Search\SectionSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class SectionSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("code", null,[
                "required" => false
            ])
            ->add("libelle_ar", null,[
                "required" => false
            ])
            ->add("libelle_fr", null,[
                "required" => false
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => SectionSearch::class,
            "method"=>"get",
            "csrf_protection"=>false
        ]);
    }

    public function getBlockPrefix()
    {
        return "filtre";
    }

}
