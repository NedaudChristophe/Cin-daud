<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Casting;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CastingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role', TextType::class, array(
                'constraints' => new NotBlank(),
                'label' => "Nom du personnage",
            ))
            ->add('creditOrder', IntegerType::class, array(
                'constraints' => new NotBlank(),
                'label' => "Position au casting ",
            ))
            ->add('movie', EntityType::class, [
                'label' => 'Choisir le nom du film ou de la série',
                'choice_label' => 'title', 
                'class' => Movie::class,
                'multiple' => false,
                'expanded' => false,
                'required' => false
            ])
            ->add('person', EntityType::class, [
                'label' => 'Choisir le nom l\'acteur/actrices',
                'choice_label' => 'CompleteName', 
                'class' => Person::class,
                'multiple' => false,
                'expanded' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Casting::class,
        ]);
    }
}
