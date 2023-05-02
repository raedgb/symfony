<?php

namespace App\Form;

use App\Entity\Participation;
use App\Entity\Covoiturage;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_participant')
            ->add('mail')
            ->add('covoiturage', EntityType::class, [
                'class' => Covoiturage::class,
                'choice_label' => function (Covoiturage $covoiturage) {
                    return sprintf('Adresse de départ : %s  / Adresse arrivé : %s / Date de départ : %s / Heure de départ : %s / Nombre de place disponible : %s / Le Prix : %s / La Description : %s / Le nom du conducteur :%s',$covoiturage->getAdresseDepart(), $covoiturage->getAdresseArrive(),$covoiturage->getDateDepart1(),
                    $covoiturage->getHeureDepart(),$covoiturage->getNbPlace(),$covoiturage->getPrix(),$covoiturage->getDescription(),$covoiturage->getNomConducteur());
                },
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
        ]);
    }
}

#->add('covoiturage', EntityType::class, [
   # 'class' => Covoiturage::class,
    #'choice_label' => 'lieu_depart',''
#])