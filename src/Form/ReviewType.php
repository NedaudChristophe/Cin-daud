<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


use Symfony\Component\Validator\Constraints\NotBlank;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, array(
                'constraints' => new NotBlank(),
            ))
            ->add('email', EmailType::class, array(
                'constraints' => new NotBlank(),
            ))
            ->add('content', TextareaType::class, array(
                'constraints' => new NotBlank(),
            ))
            ->add('rating', ChoiceType::class, [
                'choices' => [
                    'Excellent' => 5,
                    'Très bon' => 4,
                    'Bon' => 3,
                    'Peut mieux faire' => 2,
                    'A éviter' => 1
                ],
                'placeholder' => 'Votre appréciation...',
            ])
            ->add('reactions', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'placeholder' => 'Choisissez une réaction',
                'choices'  => [
                    'rire' => 'rire',
                    'Pleurer' => 'Pleurer',
                    'Réfléchir' => 'Réfléchir',
                    'Dormir' =>'Dormir' ,
                    'Rêver' => 'Rêver'        
                ]])      
           
          /* ->get('reactions')
            ->addModelTransformer(new CallbackTransformer(
             function ($reactionsArray) {
                 // transform the array to a string
                 return count($reactionsArray)? $reactionsArray[0]: null;
             },
             function ($reactionsString) {
                 // transform the string back to an array
                 return [$reactionsString];
             }
            )) */
            ->add('watchedAt', DateTimeType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                 ])
            /*->add('movie', null, [
                'placeholder' => 'Choisissez un film...',
            ]) */   
            
            
            
           
                    
                
            
        ;

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
