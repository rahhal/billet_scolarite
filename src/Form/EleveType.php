<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Entity\Etablissements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EleveType extends AbstractType
{/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('identifiant')
            ->add('nomprenom')
            ->add("date_naissance", DateType::class, [
                "widget" => "single_text",
                "format" => DateType::HTML5_FORMAT,
            ])
            ->add('lieu_naissance')
         
           ->add("etablissementAnneeDerniere", null, [
            "attr" => [
                "class" => "select-search"
            ]
        ])
        ->add("classe_annee_derniere", null, [
            "attr" => [
                "class" => "select-search"
            ]
        ])
        ->add("classeAnneeActuelle", null, [
            "attr" => [
                "class" => "select-search"
            ]
        ])
        ->add("num_ordre", IntegerType::class)
        ->add("sexe", IntegerType::class)

        ->add('cin')
      //  ->add('num_ordre_u')
        // ->add('nationalite')
        // ->add('nbr_frere_secondaire')
        // ->add('nbr_frere_universitaire')
        // ->add('est_orphelin')
        // ->add('orphelin_qui')
        // ->add('parent_divorce')
        // ->add('garde')
        // ->add('nom_pere')
        // ->add('metier_pere')
        // ->add('cin_pere')
        // ->add('date_emession_cin')
        // ->add('nom_mere')
        // ->add('metier_mere')
        // ->add('adresse_domicile')
        // ->add('adresse_travail')
        // ->add('fixe')
        // ->add('gsm')
        // ->add('email')
        // ->add('procureur')
        // ->add('ci_procureur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}
