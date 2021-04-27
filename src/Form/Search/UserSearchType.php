<?php

namespace App\Form\Search;

use App\Entity\Search\UserSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("nomPrenom", TextType::class,[
                "required" => false
            ])
            ->add("username", TextType::class,[
                "required" => false
            ])
            ->add("email", TextType::class,[
                "required" => false
            ])
            ->add("enabled", CheckboxType::class, [
                "required" => false
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => UserSearch::class,
            "method"=>"get",
            "csrf_protection"=>false
        ]);
    }

    public function getBlockPrefix()
    {
        return "filtre";
    }

}
