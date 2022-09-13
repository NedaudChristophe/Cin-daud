<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Season;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('episodeNumber', Integer::class)
            ->add('movie', EntityType::class, [
                'label' => 'Choisir le nom du film ou de la série',
                'choice_label' => 'title', // valeur de la prop à afficher dans les balises options
                'class' => Movie::class,
                'multiple' => false,
                'expanded' => false,
                'required' => false
            ])
        ;
    }
        
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
