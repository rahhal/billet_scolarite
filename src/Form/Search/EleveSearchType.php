<?php

namespace App\Form\Search;

use App\Entity\Search\EleveSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use App\Entity\Classe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Etablissements;

class EleveSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("identifiant", null,[
                "required" => false
            ])
            ->add("nomprenom", null,[
                "required" => false
            ])
            ->add("etablissementAnneeDerniere", EntityType::class, [
                "class"=>Etablissements::class,
                "required" => false,
                "attr" => [
                    "class" => "select-search"
                ]
            ])
            ->add("classeAnneeDerniere", EntityType::class, [
                "class"=>Classe::class,
                "required" => false,
                "attr" => [
                    "class" => "select-search"
                ]
            ])
            ->add("classeAnneeActuelle", EntityType::class, [
                "class"=>Classe::class,
                "required" => false,
                "attr" => [
                    "class" => "select-search"
                ]
            ])
            ->add("user", null,[
                "required" => false
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => EleveSearch::class,
            "method"=>"get",
            "csrf_protection"=>false
        ]);
    }

    public function getBlockPrefix()
    {
        return "filtre";
    }

}
