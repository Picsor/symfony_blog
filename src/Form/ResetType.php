<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ResetType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder
        ->add('username', textType::class)
        ->add('question', textType::class)
        ->add('reponse', textType::class)
        ->add('password', PasswordType::class)
        ->add('submit', SubmitType::class, [
            'label' => 'Réinitialiser'
        ]);
    }
}