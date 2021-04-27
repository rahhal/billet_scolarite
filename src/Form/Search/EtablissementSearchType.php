<?php

namespace App\Form\Search;

use App\Entity\Search\EtablissementSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use App\Entity\Gouvernorat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EtablissementSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("ministere", null,[
                "required" => false
            ])
            ->add("mandoubia", null,[
                "required" => false
            ])
            ->add("code", null,[
                "required" => false
            ])
            ->add("nom", null,[
                "required" => false
            ])
            ->add("gouvernorat", EntityType::class, [
                "class"=>Gouvernorat::class,
                "required" => false,
                "attr" => [
                    "class" => "select-search"
                ]
            ])
            ->add("ville", null,[
                "required" => false
            ])
            ->add("adresse", null,[
                "required" => false
            ])
            ->add("tel", null,[
                "required" => false
            ])
            ->add("fax", null,[
                "required" => false
            ])
            ->add("user", null,[
                "required" => false
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => EtablissementSearch::class,
            "method"=>"get",
            "csrf_protection"=>false
        ]);
    }

    public function getBlockPrefix()
    {
        return "filtre";
    }

}
