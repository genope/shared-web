<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints\ImageValidator;
use Symfony\Component\Mime\MimeTypes;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomevent')
            ->add('datedebev')
            ->add('datefinev')
            ->add('image',FileType::class,array('data_class' => null),array(
            'label' => 'Image(jpg,png,jpeg)','mapped'=>false,'required'=>false,
                'constraints' => [
        new File([
            'mimeTypes' => [
                'image/jpeg',
                'image/jpg',
            ],
            'mimeTypesMessage' => 'Image invalide : (jpg,png,jpeg)',
        ])
                ],

            ))
            ->add('nbparticip')
            ->add('description')
            ->add('lieu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
