<?php

namespace App\Form;

use App\Entity\Publication;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;


class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Nom'
                )
            ))
            ->add('description', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Description'
                )
            ))
            ->add('image',FileType::class,[
                'mapped'=>false,
                'required' =>false,
            ])
            ->add('adresse', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Adresse'
                )
            ))

            ->add('idGuest')
            ->add('region')
           /* -> add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptcha'
            ))*/
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
