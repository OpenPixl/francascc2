<?php

namespace App\Form\Admin;

use App\Entity\Admin\College;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => College::class,
        ]);
    }
}
