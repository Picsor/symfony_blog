<?php

namespace App\Form;

use App\Entity\Menu;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // No need for SubmitType, handle by CRUD template
        $builder
        ->add('name', TextType::class, [
            'required' => true,
            'attr' => ['placeholder' => 'The name of your menu...']
        ])
        ->add('starter', TextType::class, [
            'required' => false,
            'attr' => ['placeholder' => 'The starter... the beginning may I say...']
        ])
        ->add('dish', TextType::class, [
            'required' => true,
            'attr' => ['placeholder' => 'The dish... the one and only...']
        ])
        ->add('dessert', TextType::class, [
            'required' => false,
            'attr' => ['placeholder' => 'The dessert... the finale...']
        ])
        ->add('price', NumberType::class, [
            'required' => true,
            'attr' => ['placeholder' => 'The desolating price...']
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
