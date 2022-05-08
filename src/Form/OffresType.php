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
use Symfony\Component\Form\Extension\Core\Type\TextType;



class OffresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'attr' =>[

                    'id'=>'input1'
                ],
                'required'   => false
            ])
            ->add('description',TextareaType::class,[ 'required'   => false])
            ->add('datedebut', DateType::class ,array(
                    'format' => 'yyyy-MM-dd',
                    'widget' => 'single_text',
                    'required' => false,
                )
            )
            ->add('datefin', DateType::class, array(
                'format' => 'yyyy-MM-dd',
                'widget' => 'single_text',
                'required' => false,
            ))
            ->add('prix',null,[ 'required'   => false])
            ->add('ville',ChoiceType::class,[
                'choices' =>[
                    'Ariana'=>'Ariana',
                    'Beja'=>'Beja',
                    'Ben Arous'=>'Ben Arous',
                    'Bizerte'=>'Bizerte',
                    'Gabes'=>'Gabes',
                    'Gafsa'=>'Gafsa',
                    'Jendouba'=>'Jendouba',
                    'Kairouan'=>'Kairouan',
                    'Kasserine '=>'Kasserine',
                    'Kebili'=>'Kebili',
                    'Manouba'=>'Manouba',
                    'Kef'=>'Kef',
                    'Mahdia'=>'Mahdia',
                    'Médenine'=>'Médenine',
                    'Monastir'=>'Monastir',
                    'Nabeul'=>'Nabeul',
                    'Sfax'=>'Sfax',
                    'Sidi Bouzid'=>'Sidi Bouzid',
                    'Siliana'=>'Siliana',
                    'Sousse'=>'Sousse',
                    'Tataouine'=>'Tataouine',
                    'Tozeur'=>'Tozeur',
                    'Tunis'=>'Tunis',
                    'Zaghouan'=>'Zaghouan',
                ],
            ])
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
