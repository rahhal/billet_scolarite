<?php

namespace App\Form;

use App\Entity\JourFerie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;


class JourFerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add("debut", DateType::class, [
            "widget" => "single_text",
            "format" => DateType::HTML5_FORMAT,
        ])
        ->add("fin", DateType::class, [
            "widget" => "single_text",
            "format" => DateType::HTML5_FORMAT,
        ])
        ->add("libelle")
          //  ->add('annee_scolaire')
         //   ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JourFerie::class,
        ]);
    }
}
