<?php

namespace App\Form;

use App\Entity\AnneeScolaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class AnneeScolaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('annee', IntegerType::class)
            ->add("current", CheckboxType::class, [
                "required" => false
            ])
            ->add('directeur')
            ->add('surveillant')
           // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnneeScolaire::class,
        ]);
    }
}
