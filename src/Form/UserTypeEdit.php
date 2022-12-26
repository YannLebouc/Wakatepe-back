<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserTypeEdit extends AbstractType
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
                'Modérateur' => 'ROLE_MANAGER',
                'Administrateur' => 'ROLE_ADMIN',
            ],
            'multiple' => true,
            'expanded' => true,
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
        // ->add('picture', UrlType::class,
        // [
        //     "label" => "Image de profil :",
        //     "attr" => [
        //         "placeholder" => "Saisissez l'url de l'image",
        //     ]   
        // ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
