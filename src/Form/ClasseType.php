<?php

namespace App\Form;

use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('code',TextType::class, [
            //     'required'=> false,
            //      ])
            ->add('libelle')
            ->add("niveau", null, [
                "required"=>true,
                "attr" => [
                    "class" => "select-search niveaux"
                ]
            ])
           // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}
