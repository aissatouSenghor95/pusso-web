<?php

namespace App\Form;

use App\Entity\Demande;
use App\Entity\Organisme;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

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
            ->add('contact', TextType::class,array(
                'required'  => true,
                'attr' => ['data-mask' => "+221 99 999 99 99"],
                'constraints'  => [
                    new NotBlank([
                        'message'   => 'Veuillez saisir un numéro de téléphone.'
                    ])
                ]
            ))

            ->add('service', EntityType::class,
                array(
                    'class' => Service::class,
                    'choice_label' => 'libelle'
                )
            )
            ->add('organisme', EntityType::class,
                array(
                    'class' => Organisme::class,
                    'choice_label' => 'nom'
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
