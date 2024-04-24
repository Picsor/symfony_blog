<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Menu;
use App\Entity\Reservation;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // No need for SubmitType, handle by CRUD template
        $builder
        ->add('name', TextType::class, [
            'required' => true,
            'attr' => ['placeholder' => 'Votre nom de famille']
        ])
        ->add('firsName', TextType::class, [
            'required' => true,
            'attr' => ["placeholder' => 'Votre prénom"]
        ])
        ->add('email', TextareaType::class, [
            'required' => true,
            'attr' => ['placeholder' => 'votre email']
        ])
        ->add('date', DateTimeType::class, [
            'required'=> true,
            'attr'=> [ 'placeholder' => 'date de votre repas']
        ])
        ->add('envoyer', SubmitType::class)
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}