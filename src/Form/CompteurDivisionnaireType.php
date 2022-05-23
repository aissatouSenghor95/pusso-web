<?php

namespace App\Form;

use App\Entity\ChoixPresentationFacture;
use App\Entity\Demande;
use App\Entity\Organisme;
use App\Entity\Pays;
use App\Entity\QualiteDemandeur;
use App\Entity\Service;
use App\Entity\TypeBranchement;
use App\Entity\TypeConstruction;
use App\Entity\TypeDemandeur;
use App\Entity\TypeInscription;
use App\Entity\TypeLieu;
use App\Entity\TypeLocal;
use App\Entity\TypePieceIdentite;
use App\Entity\TypeUsage;
use App\Helper\OrganismeEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CompteurDivisionnaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('service', EntityType::class,
                array(
                    'class' => Service::class,
                    'choice_label' => 'libelle',
                    'disabled' => true
                )
            )
            ->add('organisme', EntityType::class,
                array(
                    'class' => Organisme::class,
                    'choice_label' => 'nom',
                    'disabled' => true
                )
            )
            ->add('nbrCptrDiv', IntegerType::class,
                array(
                    'attr' => ['min' => 3]
                )
            )
            ->add('contact', TextType::class, array(
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un numéro de téléphone.'
                    ])
                ]
            ))
            ->add('cin', TextType::class,
                array(
                    'required' => true,
                    'attr' => ['placeholder' => "Numéro de piéce d'identité"],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir votre numéro de pièce d\'identité.'
                        ])
                    ]
                )
            )
            ->add('adresse', TextareaType::class,
                array(
                    'required' => true,
                    'attr' => ['placeholder' => 'Adresse de facturation']
                )
            )
            ->add('telDomicile', TextType::class,
                array(
                    'required' => true,
                    'attr' => ['data-mask' => "+221 99 999 99 99"],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir un numéro de téléphone du domicile.'
                        ])
                    ]
                )
            )
            ->add('nature', TextType::class,
                array(
                    'required' => false
                )
            )
            ->add('mandataire', TextType::class,
                array(
                    'required' => false
                )
            )
            ->add('typePieceIdentite', EntityType::class,
                array(
                    'class' => TypePieceIdentite::class,
                    'choice_label' => 'libelle'
                )
            )
            ->add('accessibiliteCompteurs', CheckboxType::class, [
                'label'    => 'Accessibilité des compteurs',
                'required' => false,
            ])
            ->add('poseRobinet', CheckboxType::class, [
                'label'    => 'Pose d\'un robinet après compteur',
                'required' => false,
            ])
            ->add('respectDistance', CheckboxType::class, [
                'label'    => 'Respect d\'une distance de 25,5cm pour l\'emplacement du compteur',
                'required' => false,
            ])
            ->add('raccordement', CheckboxType::class, [
                'label'    => 'Raccordement des divisionnaires après le compteur principal',
                'required' => false,
            ])
            ->add('poseClapet', CheckboxType::class, [
                'label'    => 'Pose d\'un caplet anti-retour pour chaque compteur divisionnaire',
                'required' => false,
            ])
            ->add('installationHorizontale', CheckboxType::class, [
                'label'    => 'Installation horizontale',
                'required' => false,
            ])
            ->add('numeration', CheckboxType::class, [
                'label'    => 'Numérotation de l\'emplacement des compteurs et des appartements',
                'required' => false,
            ])
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
                        'mimeTypesMessage' => 'Le fichier joint n\'est pas une image valide (jpeg, png, gif).',
                        'maxSizeMessage' => 'La taille maximale permise est de 5M.'
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
            'organisme' => null,
            'service'   => null
        ]);
    }
}
