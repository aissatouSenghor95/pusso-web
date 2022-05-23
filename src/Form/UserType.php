<?php

namespace App\Form;

use App\Entity\Organisme;
use App\Entity\Role;
use App\Entity\Sexe;
use App\Entity\Utilisateur;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                'required'  => true
            ))
            ->add('prenom', TextType::class, array(
                'required'  => true
            ))
            ->add('numeroTel', TextType::class,array(
                'required'  => true,
                'attr' => ['data-mask' => "+221 99 999 99 99"],
                'constraints'  => [
                    new NotBlank([
                        'message'   => 'Veuillez saisir un numéro de téléphone.'
                    ])
                ]
            ))
            ->add('username', EmailType::class, array(
                'constraints' =>[
                    new Email([
                        'message'=>'This is not the correct email format'
                    ]),
                    new NotBlank([
                        'message' => 'This field can not be blank'
                    ])
                ],
            ))
            ->add('birthday', DateTimeType::class,
                array(
                    'required'  => false,
                    'widget'    => 'single_text',
                    'format'    => 'dd/mm/yyyy',
                    'html5'     => false
                )
            )

            ->add('sexe', EntityType::class,
                    array(
                        'class' => Sexe::class,
                        'choice_label' => 'libelle'
                    )
                )
            ->add('ville', EntityType::class,
                array(
                    'class' => Ville::class,
                    'choice_label' => 'nom'
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
