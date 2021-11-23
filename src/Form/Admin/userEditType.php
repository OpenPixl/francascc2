<?php

namespace App\Form\Admin;

use App\Entity\Admin\College;
use App\Entity\Admin\user;
use phpDocumentor\Reflection\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class userEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'administrateur' => "administrator",
                    'membre' => 'member',
                ],
            ])
            ->add('firstName')
            ->add('lastName')
            ->add('loginName')
            ->add('avatarFile')
            ->add('adress1')
            ->add('adress2')
            ->add('zipcode')
            ->add('city')
            ->add('phoneDesk')
            ->add('phoneGsm')
            ->add('college',EntityType::class,[
                'class' => College::class,
                'placeholder' => '-- Choisir le college --',
                'required' => false,
                'label'=> "Thème du projet",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => user::class,
        ]);
    }
}
