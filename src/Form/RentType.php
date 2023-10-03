<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Nom de la location',
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('type', TypeRent::class, [
                'label' => 'Type de location',
                'choice_label' => 'label',
                'multiple' => false,
                'expanded' => true,
                'attr' => ['class' => 'form-control mb-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
