<?php

namespace App\Form;

use App\Entity\Colis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ColisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorie')
            ->add('poids')
            ->add('idUser')
            ->add('adressDepart')
            ->add('adressArrive')
            ->add('numDes')
            ->add('image')
            ->add('image',FileType::class,[
                            'label' => '',
                            'mapped' => false,
                            'required' => false,
                            'constraints' => [
                                new File([
                                    'maxSize' => '9Mi',
                                    'mimeTypesMessage' => 'Please upload a valid image file',
                                ])
                                ],
                                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Colis::class,
        ]);
    }
}
