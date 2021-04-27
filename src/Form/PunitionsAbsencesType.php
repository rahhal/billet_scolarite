<?php

namespace App\Form;

use App\Entity\PunitionsAbsences;
use App\Entity\Matiere;
use App\Entity\AnneeScolaire;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PunitionsAbsencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = $options['type'];
        $builder
        ->add('type', TextType::class, [
            'data' => $type
        ])
        ->add('annee_scolaire', EntityType::class, [
            'class'=>AnneeScolaire::class,
            'data' => $options['anneeScolaire']
        ])->add('classe', null, [
                'attr' => [
                    'class' => 'classe'
                ]
            ])
            ->add('eleve', null, [
                'required'=>true,
                'attr' => [
                    'class' => 'eleve'
                ]
            ])
            ->add('dateDebut', DateType::class, [
                "widget" => "single_text",
                "format" => DateType::HTML5_FORMAT,
                'required' => false,
                'attr' => [
                    'class' => 'dateDebut'
                ]
            ]);

    if (in_array($type, [PunitionsAbsences::ELIMINE]))
        $builder->add('dateFin', DateType::class, [
            "widget" => "single_text",
            "format" => DateType::HTML5_FORMAT,
            'required' => false,
            'attr' => [
                'class' => 'dateFin'
            ]
        ]);

    if (in_array($type, [PunitionsAbsences::EXCLUSION,PunitionsAbsences::EXPULSION]))
        $builder
            ->add('matiere',EntityType::class,[
                'class'=>Matiere::class,
                'query_builder'=>function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->join('m.enseignants','e');
                },
                'required' => true,
                'attr' => [
                    'class' => 'matiere'
                ]
            ])
            ->add('enseignant',null,[
                'required' => true,
                'attr' => [
                    'class' => 'enseignant'
                ]
            ]);

    if (in_array($type, [PunitionsAbsences::EXCLUSION,PunitionsAbsences::EXPULSION,PunitionsAbsences::AVERTISSEMENT]))
        $builder->add('raison');

    if (in_array($type, [PunitionsAbsences::AVERTISSEMENT,PunitionsAbsences::CONSEIL,PunitionsAbsences::ELIMINE]))
        $builder->add('objet');

    if (!in_array($type, [PunitionsAbsences::AVERTISSEMENT,PunitionsAbsences::ELIMINE]))
        $builder->add('heure', TimeType::class, [
            "widget" => "single_text",
            'required' => false
        ]);

    if (in_array($type, [PunitionsAbsences::ABSENCE,PunitionsAbsences::EXPULSION]))
        $builder->add('nbrJour',null,[
            'attr'=>[
                'min'=>0
            ]
        ]);

    $required=$options['espace_user'];
    if (in_array($type, [PunitionsAbsences::ABSENCE,PunitionsAbsences::EXCLUSION,PunitionsAbsences::RETARD]))
        $builder->add('modeReglement',ChoiceType::class,[
            'choices'=>[
                'Aucun'=>'Aucun',
                'Présence du parents'=>'Présence du parents',
                'Certificat médicale'=>'Certificat médicale',
                'Attestation de présence'=>'Attestation de présence',
                'Autres documents'=>'Autres documents'
            ],
            'attr' => [
                'class' => 'reglement'
            ],
            'required' => $required
        ]);

    if (in_array($type, [PunitionsAbsences::ABSENCE]))
        $builder->add('nbrHeure',null,[
            'attr'=>[
                'min'=>0
            ]
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PunitionsAbsences::class,
            'type' => null,
            'espace_user' => null,
            'anneeScolaire' => null
        ]);
    }
}
