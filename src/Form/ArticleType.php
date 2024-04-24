<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Menu;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // No need for SubmitType, handle by CRUD template
        $builder
        ->add('name', TextType::class, [
            'required' => true,
            'attr' => ['placeholder' => 'Nom de votre menu']
        ])
        ->add('startDish', TextType::class, [
            'required' => true,
            'attr' => ["placeholder' => 'Nom de l'entrée"]
        ])
        ->add('mainDish', TextareaType::class, [
            'required' => true,
            'attr' => ['placeholder' => 'Nom du plat principal']
        ])
        ->add('desert', TextareaType::class, [
            'required'=> true,
            'attr'=> [ 'placeholder' => 'Nom du dessert']
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}