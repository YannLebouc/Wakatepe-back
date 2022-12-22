<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\MainCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, 
            [
                "label" => "Nom de la catégorie :",
                "attr" => [
                    "placeholder" => "saisissez le nom de la catégorie",
                ]
            ])
            // ->add('picture', UrlType::class, 
            // [
            //     "label" => "URL de l'image de la catégorie :",
            //     "attr" => [
            //         "placeholder" => "http://...."
            //     ]
            // ])
            ->add('isActive', ChoiceType::class,
            [
                "placeholder" => "Active",
                "label" => "Active",
                "expanded" => true,
                "multiple" => false,
                "choices" => [
                    "oui" => true,
                    "non" => false
                ]
            ])

            ->add('mainCategory', EntityType::class,
            [
                'class' => MainCategory::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
