<?php

namespace App\Form;

use App\Entity\Offres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;



class OffresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description',TextareaType::class)
            ->add('datedebut', DateType::class, array(
                    'format' => 'yyyy-MM-dd',
                    'widget' => 'single_text',
                )
            )
            ->add('datefin', DateType::class, array(
                'format' => 'yyyy-MM-dd',
                'widget' => 'single_text',
            ))
            ->add('prix')
            ->add('ville')
            ->add('categ',ChoiceType::class,[
                'choices' =>[
                        'Appartement'=>'Appartement',
                        'Maison'=>'Maison',
                        'Chambre'=>'Chambre',
                        'Voiture'=>'Voiture',
                        'Moto'=>'Moto',
                        'Vélo'=>'Vélo',
                ],
            ])
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
            ->add('enregitrer',SubmitType::class,[
                'attr' =>[
                    'style'=>'background-color:green',
                    'class' =>'button fullwidth_block margin-top-30 btnn'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offres::class,
        ]);
    }
}
