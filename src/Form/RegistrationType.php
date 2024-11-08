<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2]),
                ],
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control form-control-lg'],
            ])
            ->add('lastName', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2]),
                ],
                'label' => 'Nom',
                'attr' => ['class' => 'form-control form-control-lg'],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                ],
                'label' => 'Email',
                'attr' => ['class' => 'form-control form-control-lg'],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe', 'attr' => ['class' => 'form-control form-control-lg']],
                'second_options' => ['label' => 'Confirmation du mot de passe', 'attr' => ['class' => 'form-control form-control-lg']],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 6]),
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ])
            ->add('validate', SubmitType::class, [
                'label' => 'Valider l\'inscription',
                'attr' => ['class' => 'btn btn-primary btn-lg'],
            ]);
    }
}
