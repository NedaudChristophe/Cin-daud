<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\NotBlank;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, array(
                'constraints' => new NotBlank(),
                'label' => "Titre du film",
            ))
            ->add('type', ChoiceType::class, [
                'label' => "Choisissez le Type",
                'choices'  => [
                    'série 🙃' => 'série',
                    'film 😀' => 'film'
                ],
                // Bontons radios
                'expanded' => true,                
            ])
            
            ->add('releaseDate', DateTimeType::class, array(
                'constraints' => new NotBlank(),
                'label' => 'Vous avez vu ce film le...',
                'widget' => 'single_text',
                'input' => 'datetime',
            ))
            
            ->add('duration', IntegerType::class, array(
                'constraints' => new NotBlank(),
                'help' => 'Durée en minutes',
                'label' => 'Durée du film'
            ))
            
            ->add('summary', TextareaType::class, array(
                'constraints' => new NotBlank(),
            ))
            
            ->add('synopsis', TextareaType::class, array(
                'constraints' => new NotBlank(),
            ))
            
            ->add('poster', UrlType::class, array(
                'constraints' => new NotBlank(),
                'help' => 'Url de l\'image'
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
                
                ->add('genre', EntityType::class, [
                    'label' => 'Choisir le ou les genres du média',
                    'choice_label' => 'name', 
                    'class' => Genre::class,
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false
                ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
