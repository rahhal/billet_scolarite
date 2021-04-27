<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $mode = $options['mode'];
        $builder
             ->add("email", EmailType::class)
           // ->add('password')
            ->add('username')
            ->add('nomPrenom')
            
            // ->add('roles', ChoiceType::class, [
            //     'choices' => [
            //         'Administrateur ' => 'ROLE_ADMIN'
            //     ],
            //     'expanded' => true,
            //     'multiple' => true
            // ])
            ->add("enabled", CheckboxType::class, [
                "required" => false,
                "data"=>true
            ]);
        if ($mode == "add")
            $builder->add("password");
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            "mode" => null
        ]);
    }
}
