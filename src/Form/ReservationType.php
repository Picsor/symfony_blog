<?php

namespace App\Form;
use App\Entity\Reservation;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // No need for SubmitType, handle by CRUD template
        $builder
        ->add('firstname', TextType::class, [
            'required' => true,
            'attr' => ['placeholder' => 'Firstname']
        ])
        ->add('lastname', TextType::class, [
            'required' => true,
            'attr' => ['placeholder' => 'Lastname']
        ])
        ->add('email', EmailType::class, [
            'required' => true,
            'attr' => ['placeholder' => 'email']
        ])
        ->add('date', DateTimeType::class, [
            'required' => true
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
