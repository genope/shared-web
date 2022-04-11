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
            ->add('refProd',null,[
                'required'   => false,
                'empty_data' => '',
            ])
            ->add('designation',null,[
                'required'   => false,
                'empty_data' => '',
            ])
            ->add('description',TextareaType::class,[
                'required'   => false,
                'empty_data' => '',
            ])
            ->add('image',FileType::class,[
                'data_class' => null,
                'label'     => 'Image',
                'required'  => false,

            ])
            ->add('prix',null,[
                'required'   => false,
                'empty_data' => '',
            ])
            ->add('qteStock',null,[
                'required'   => false,
                'empty_data' => '',
            ])
            ->add('region',null,[
                'required'   => false,
                'empty_data' => '',
            ])
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
