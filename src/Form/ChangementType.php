<?php

namespace App\Form;

use App\Entity\ChoixPresentationFacture;
use App\Entity\Demande;
use App\Entity\Organisme;
use App\Entity\Pays;
use App\Entity\QualiteDemandeur;
use App\Entity\Service;
use App\Entity\TypeBranchement;
use App\Entity\TypeChangement;
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

class ChangementType extends AbstractType
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
            ->add('lieuBranchement', TextareaType::class,
                array(
                    'required' => true,
                    'attr' => ['placeholder' => 'Lieu du branchement'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir l\'adresse de branchement.'
                        ])
                    ]
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
            ->add('telBureau', TextType::class,
                array(
                    'required' => false,
                    'attr' => ['placeholder' => 'Numéro de tél. professionnel'],
                )
            )
            ->add('adressePro', TextareaType::class,
                array(
                    'required' => false,
                    'attr' => ['placeholder' => 'Adresse professionnelle'],
                )
            )
        ;

        if (strcmp($options['organisme'], OrganismeEnum::ORG_SENELEC) == 0) {
            $builder
                ->add('typeChangement', EntityType::class,
                    array(
                        'class' => TypeChangement::class,
                        'choice_label' => 'libelle'
                    )
                )
                ->add('typePieceIdentite', EntityType::class,
                    array(
                        'class' => TypePieceIdentite::class,
                        'choice_label' => 'libelle'
                    )
                )
                ->add('typeInscription', EntityType::class,
                    array(
                        'class' => TypeInscription::class,
                        'choice_label' => 'libelle'
                    )
                )
                ->add('presentationFacture', EntityType::class,
                    array(
                        'class' => ChoixPresentationFacture::class,
                        'choice_label' => 'libelle'
                    )
                )
                ->add('typeLocal', EntityType::class,
                    array(
                        'class' => TypeLocal::class,
                        'choice_label' => 'libelle'
                    )
                )
                ->add('numeroRegistre', TextType::class,
                    array(
                        'required' => false,
                        'attr' => ['placeholder' => 'Numéro'],
                    )
                )
                ->add('prelevementBancaire', ChoiceType::class,
                    array(
                        'choices' => array(
                            'OUI' => true,
                            'NON' => false
                        ),
                        'label' => 'Prélèvement bancaire: ',
                        'required' => true,
                        'empty_data' => false
                    )
                )
                ->add('dateNaissance', DateTimeType::class,
                    array(
                        'required'  => false,
                        'widget'    => 'single_text',
                        'format'    => 'dd/mm/yyyy',
                        'html5'     => false
                    )
                )
                ->add('banque', TextType::class,
                    array(
                        'required' => false,
                        'attr' => ['placeholder' => 'Nom de votre banque'],
                    )
                )
                ->add('anciennePolice', TextType::class,
                    array(
                        'required' => false,
                        'attr' => ['placeholder' => 'Ancien numéro de police'],
                    )
                )
                ->add('ancienneAdresse', TextareaType::class,
                    array(
                        'required' => false,
                        'attr' => ['placeholder' => 'Ancienne Adresse'],
                    )
                )
                ->add('numeroPolice', TextType::class,
                    array(
                        'required' => false,
                        'attr' => ['placeholder' => 'Numéro Police'],
                    )
                )
                ->add('nationalite', EntityType::class,
                    array(
                        'class' => Pays::class,
                        'choice_label' => 'nicename'
                    )
                )
                ->add('employeur', TextareaType::class,
                    array(
                        'required' => false,
                        'attr' => ['placeholder' => 'Employeur'],
                    )
                )
                ->add('profession', TextareaType::class,
                    array(
                        'required' => false,
                        'attr' => ['placeholder' => 'Votre profession'],
                    )
                )
                ->add('proprietaire', TextareaType::class,
                    array(
                        'required' => false,
                        'attr' => ['placeholder' => 'Nom du propriétaire'],
                    )
                )
                ->add('numCompte', TextType::class,
                    array(
                        'required' => false,
                        'attr' => ['placeholder' => 'Numéro compte bancaire'],
                    )
                )
                ->add('nbrFoyersLumineux', IntegerType::class,
                    array(
                        'attr' => ['min' => 0]
                    )
                )
                ->add('nbrFrigos', IntegerType::class,
                    array(
                        'attr' => ['min' => 0]
                    )
                )
                ->add('nbrChauffeEau', IntegerType::class,
                    array(
                        'attr' => ['min' => 0]
                    )
                )
                ->add('nbrFersRepasser', IntegerType::class,
                    array(
                        'attr' => ['min' => 0]
                    )
                )
                ->add('nbrVentilateurs', IntegerType::class,
                    array(
                        'attr' => ['min' => 0]
                    )
                )
                ->add('nbrMachinesLaver', IntegerType::class,
                    array(
                        'attr' => ['min' => 0]
                    )
                )
                ->add('nbrTeleviseurs', IntegerType::class,
                    array(
                        'attr' => ['min' => 0]
                    )
                )
                ->add('nbrClimatiseurs', IntegerType::class,
                    array(
                        'attr' => ['min' => 0]
                    )
                )
                ->add('nbrMoteursDivers', IntegerType::class,
                    array(
                        'attr' => ['min' => 0]
                    )
                )
                ->add('nbrCongelateurs', IntegerType::class,
                    array(
                        'attr' => ['min' => 0]
                    )
                )
                ->add('nbrWifi', IntegerType::class,
                    array(
                        'attr' => ['min' => 0]
                    )
                )
                ->add('nbrOrdinateurs', IntegerType::class,
                    array(
                        'attr' => ['min' => 0]
                    )
                )
            ;
        }

        $builder->add('piFile', VichImageType::class,
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
            'organisme' => null
        ]);
    }
}
