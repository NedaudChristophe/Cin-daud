<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', UrlType::class)
            ->add('roles', ChoiceType::class,
            [
                'choices' => [
                    'user' => 'ROLE_USER',
                    'admin' => 'ROLE_ADMIN',
                    'manager' => 'ROLE_MANAGER',
                ],
                "multiple" => true,
                // radio buttons or checkboxes
                "expanded" => false
            ])
            
                
            ->add('password', PasswordType::class, [
                
                'mapped' => true,
                'required' => false,
               //'always_empty' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]
            ])
            
            ->add('confirmPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'required' => false,
            ])
            
            
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event)
                {
                    $formulaire = $event->getForm();

                     /** @var User $userEntity */
                    $userEntity = $event->getData();

                    if ($userEntity->getId() !== null)
                    {   
                        $formulaire->add('password', PasswordType::class, [
                            'mapped' => false,
                            'attr' => [
                                'placeholder' => 'Laissez vide si inchangé'
                            ]
                        ]);
                    } else {
                        $formulaire->add('password', PasswordType::class, 
                            [
                                'empty_data' => '',
                                'constraints' => [
                                    new NotBlank(),
                                    //new Regex(
                                       // "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
                                       // "Le mot de passe doit contenir au minimum 8 caractères, une majuscule, un chiffre et un caractère spécial"
                                   // ),
                                ]
                            ]);
                    }

                    

                });
        ;
    }
            


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
