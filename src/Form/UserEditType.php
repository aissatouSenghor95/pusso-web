<?php

namespace App\Form;

use App\Entity\Sexe;
use App\Entity\Utilisateur;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('numeroTel')
            ->add('username', EmailType::class, array(
                'constraints' =>[
                    new Email([
                        'message'=> 'Veuillez entrer une adresse e-mail correcte.'
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse e-mail'
                    ])
                ],
            ))
            ->add('ville', EntityType::class,
                array(
                    'class' => Ville::class,
                    'choice_label' => 'nom'
                )
            )
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => array('label' => 'Nouveau mot de passe'),
                'second_options' => array('label' => 'Confirmer le mot de passe'),
                'invalid_message' => 'Les deux mots de passe ne sont pas identiques.',
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

            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
