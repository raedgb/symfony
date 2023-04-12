<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('pwd')
            ->add('numtel')
            ->add('datenaissc')
            ->add('adresse')
            ->add('sexe')
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'passager ' => 'passager',
                    'livreur ' => 'livreur',
                    'conducteur ' => 'conducteur',
                ],
                'placeholder' => 'Choisissez votre role',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
