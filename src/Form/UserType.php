<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,array(
                'attr' => array(
                    'placeholder' => 'Name'
                )
            ))
            ->add('prenom',TextType::class,array(
                'attr' => array(
                    'placeholder' => 'LastName'
                )
            ))
            ->add('email',EmailType::class,array(
                'attr' => array(
                    'placeholder' => 'Email'
                )
            ))
            ->add('password',PasswordType::class,array(
                'attr' => array(
                    'placeholder' => '***********'
                )
            ))
            ->add('datedenaissance')
            ->add('telephone')
            ->add('role')
            ->add('etat')
            ->add('adressHost',TextType::class, ['attr' => ['id' => 'searchTextField','autocomplete'=>'on']])
            ->add('imageCin')
            ->add('imageProfile')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
