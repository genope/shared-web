<?php

namespace App\Form;

use App\Entity\Categorieproduit;
use App\Entity\Produit;
use PHPUnit\TextUI\XmlConfiguration\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('refProd')
            ->add('designation')
            ->add('description',TextareaType::class)
            ->add('image',FileType::class,[
                'data_class' => null,
                'label'     => 'image',
                'required'  => false,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'maxSize' => '6000k',

                        'mimeTypesMessage' => 'Veuillez uploader une image valide'
                    ])
                ]
            ])
            ->add('prix')
            ->add('qteStock')
            ->add('region')
            ->add('nomcategorie',EntityType::class,[
                'class' => categorieproduit::class,
                'choice_label' => 'nomCategorie',
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
