<?php

namespace App\Form\Admin;

use App\Entity\Admin\College;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CollegeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('address')
            ->add('complement')
            ->add('zipcode')
            ->add('city')
            ->add('collegeEmail')
            ->add('groupEmail')
            ->add('collegePhone')
            ->add('groupPhone')
            ->add('animateur')
            ->add('GroupDescription')
            ->add('logoFile', VichImageType::class, [
                'required' => false,
            ])
            ->add('headerFile', VichImageType::class, [
                'required' => false,
            ])
            ->add('workMeeting')
            ->add('banniereFilename', FileType::class, [
                'label' => 'Banniere au format : png ou jpg',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Attention, veuillez charger un fichier image.',
                    ])
                ],
            ])
            // ...
            ->add('vignetteFilename', FileType::class, [
                'label' => 'vignette au format : png ou jpg',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => College::class,
        ]);
    }
}
