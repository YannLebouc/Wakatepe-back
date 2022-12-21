<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, 
            [
                "label" => "Adresse mail :",
                "attr" => [
                    "placeholder" => "adresse@mail.xyz",
                ]
            ])
            ->add('roles', ChoiceType::class, 
            [
                'choices'  => [
                    'Utilisateur' => 'ROLE_USER',
                    'Modérateur' => 'ROLE_MANAGER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('password', PasswordType::class, 
            [
                "label" => "Mot de passe:",
                "attr" => [
                    "placeholder" => "Mot de passe",
                    // "placeholder" => "Le mot de passe doit contenir au minimum 8 caractères, une majuscule, un chiffre et un caractère spécial",
                ],
                'empty_data' => '',
                'constraints' => [
                    new NotBlank(),
                    // new Regex(
                    //     "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
                    //     "Le mot de passe doit contenir au minimum 8 caractères, une majuscule, un chiffre et un caractère spécial"
                    // ),
                ]
            ])
            ->add('alias', TextType::class,
            [
                "label" => "Pseudonyme :",
                "attr" => [
                    "placeholder" => "Saisissez le nom que vous voulez utiliser",
                ]
            ])
            ->add('phoneNumber', TextType::class,
            [
                'required' => false,
                "label" => "n° de téléphone :",
                "attr" => [
                    "placeholder" => "Facultatif",
                ]
            ])
            ->add('zipcode', TextType::class,
            [
                "label" => "Code postal :",
                "attr" => [
                    "placeholder" => "XXXXX",
                ]
            ])
            ->add('firstname', TextType::class,
            [
                "label" => "Prénom :",
                "attr" => [
                    "placeholder" => "Saisissez votre prénom",
                ]  
            ])
            ->add('lastname', TextType::class,
            [
                "label" => "Nom :",
                "attr" => [
                    "placeholder" => "Saisissez votre nom",
                ]    
            ])
            // ->add('picture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
