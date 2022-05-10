<?php

namespace App\Form;

use App\Entity\Commentaire;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nom', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Objet'
                )
            ))
            ->add('comment',null,array('attr'=>['maxlength'=>7000,'placeholder' => 'Votre message...']))
            ->add('note',ChoiceType::class,[
                'choices'=>['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5']
            ])



        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
