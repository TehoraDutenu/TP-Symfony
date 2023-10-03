<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfficeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('userInfo', UserInfoType::class, [
                'label' => false,
            ])
            ->add('typeRent', EntityType::class, [
                'label' => false,
                'class' => TypeRent::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('rent', EntityType::class, [
                'label' => false,
                'class' => Rent::class,
                'image' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['class' => 'form']
        ]);
    }
}
