<?php

namespace App\Form;

use App\Entity\Demande;
use App\Entity\Organisme;
use App\Entity\Service;
use App\Entity\TypeLieu;
use App\Entity\TypeUsage;
use App\Helper\OrganismeEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AbonnementMutationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('service', EntityType::class,
                array(
                    'class' => Service::class,
                    'choice_label' => 'libelle',
                    'disabled'  => true
                )
            )
            ->add('organisme', EntityType::class,
                array(
                    'class' => Organisme::class,
                    'choice_label' => 'nom',
                    'disabled'  => true
                )
            )

            ->add('contact', TextType::class,array(
                'required'  => true,
                'attr' => ['data-mask' => "+221 99 999 99 99"],
                'constraints'  => [
                    new NotBlank([
                        'message'   => 'Veuillez saisir un numéro de téléphone.'
                    ])
                ]
            ))
            ->add('cin', TextType::class,
                array(
                    'required'  => true,
                    'attr' => ['placeholder' => "Numéro de piéce d'identité"],
                    'constraints'  => [
                        new NotBlank([
                            'message'   => 'Veuillez saisir votre numéro de pièce d\'identité.'
                        ])
                    ]
                )
            )
            ->add('adresse', TextareaType::class,
                array(
                    'required'  => true,
                    'attr'  => ['placeholder' => 'Adresse de facturation']
                )
            )

            ->add('nom', TextType::class,
                array(
                    'required'  => true,
                    'attr'  => ['placeholder' => 'Nom']
                )
            )

            ->add('prenom', TextType::class,
                array(
                    'required'  => true,
                    'attr'  => ['placeholder' => 'Prénom']
                )
            )

            ->add('email', TextType::class,
                array(
                    'required'  => true,
                    'attr'  => ['placeholder' => 'Adresse Email']
                )
            )

            ->add('lieuBranchement', TextareaType::class,
                array(
                    'required'  => true,
                    'attr' => ['placeholder' => 'Lieu du branchement'],
                    'constraints'  => [
                        new NotBlank([
                            'message'   => 'Veuillez saisir l\'adresse de branchement.'
                        ])
                    ]
                )
            )

            ->add('piFile', VichImageType::class,
                array(
                    'required' => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/gif',
                                'image/png',
                            ],
                            'mimeTypesMessage'  => 'Le fichier joint n\'est pas une image valide (jpeg, png, gif).',
                            'maxSizeMessage'    => 'La taille maximale permise est de 5M.'
                        ])
                    ],
                    'allow_delete' => false,
                    'download_label' => true,
                    'download_uri' => false,
                    'image_uri' => false
                )
            )

            ->add('contLocFile', VichImageType::class,
                array(
                    'required' => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/gif',
                                'image/png',
                            ],
                            'mimeTypesMessage'  => 'Le fichier joint n\'est pas une image valide (jpeg, png, gif).',
                            'maxSizeMessage'    => 'La taille maximale permise est de 5M.'
                        ])
                    ],
                    'allow_delete' => false,
                    'download_label' => true,
                    'download_uri' => false,
                    'image_uri' => false
                )
            )
            ->add('certHerFile', VichImageType::class,
                array(
                    'required' => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/gif',
                                'image/png',
                            ],
                            'mimeTypesMessage'  => 'Le fichier joint n\'est pas une image valide (jpeg, png, gif).',
                            'maxSizeMessage'    => 'La taille maximale permise est de 5M.'
                        ])
                    ],
                    'allow_delete' => false,
                    'download_label' => true,
                    'download_uri' => false,
                    'image_uri' => false
                )
            )
            ->add('mgFile', VichImageType::class,
                array(
                    'required' => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/gif',
                                'image/png',
                            ],
                            'mimeTypesMessage'  => 'Le fichier joint n\'est pas une image valide (jpeg, png, gif).',
                            'maxSizeMessage'    => 'La taille maximale permise est de 5M.'
                        ])
                    ],
                    'allow_delete' => false,
                    'download_label' => true,
                    'download_uri' => false,
                    'image_uri' => false
                )
            )
            ->add('ccdFile', VichImageType::class,
                array(
                    'required' => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/gif',
                                'image/png',
                            ],
                            'mimeTypesMessage'  => 'Le fichier joint n\'est pas une image valide (jpeg, png, gif).',
                            'maxSizeMessage'    => 'La taille maximale permise est de 5M.'
                        ])
                    ],
                    'allow_delete' => false,
                    'download_label' => true,
                    'download_uri' => false,
                    'image_uri' => false
                )
            )
            ->add('statusCommerceFile', VichImageType::class,
                array(
                    'required' => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/gif',
                                'image/png',
                            ],
                            'mimeTypesMessage'  => 'Le fichier joint n\'est pas une image valide (jpeg, png, gif).',
                            'maxSizeMessage'    => 'La taille maximale permise est de 5M.'
                        ])
                    ],
                    'allow_delete' => false,
                    'download_label' => true,
                    'download_uri' => false,
                    'image_uri' => false
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
            'morale'  => false
        ]);
    }
}
