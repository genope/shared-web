<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class HostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('cin', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Cin'
                )
            ))
            ->add('nom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Name'
                )
            ))
            ->add('prenom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'LastName'
                )
            ))
            ->add('email', EmailType::class, array(
                'attr' => array(
                    'placeholder' => 'Email'
                )
            ))
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'user.password.first'],
                'second_options' => ['label' => 'user.password.second'],
            ])
            ->add('datedenaissance', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'format' => 'yyyy-MM',
                'attr' => ['class' => 'js-datepicker']
            ])
            ->add('telephone', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Phone number'
                )
            ))
            ->add('adressHost', TextType::class, ['attr' => ['id' => 'searchTextField', 'autocomplete' => 'on']])

            ->add('imageProfile', FileType::class, ['attr' => ['class' => 'custom-file-input'],
                'label' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid ImageFile',
                    ])
                ],
            ])
            ->add('imageCin', FileType::class, ['attr' => ['class' => 'custom-file-input'],
                'label' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid ImageFile',
                    ])
                ],
            ])
           ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,
        ]);
    }
}
