<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('player1', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'email',
        ])
        ->add('player2', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'email',
        ])
        ;
    }
}
