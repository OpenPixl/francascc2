<?php

namespace App\Form\Webapp;

use App\Entity\Webapp\Articles;
use App\Entity\Webapp\Support;
use App\Entity\Webapp\Theme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'label'=> 'titre',
            ])
            ->add('intro',TextType::class,[
                'label'=> 'titre',
            ])
            ->add('isShowReadMore')
            ->add('content',TextareaType::class,[
                'label'=> "Contenu de l'article",
            ])
            ->add('theme',EntityType::class,[
                'class' => Theme::class,
                'label'=> "Thème du projet",
            ])
            ->add('support',EntityType::class,[
                'class' => Support::class,
                'label'=> "Support du Projet",
            ])
            ->add('imageFile', VichImageType::class, ['required' => false,])
            ->add('docFile', VichFileType::class, ['required' => false,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
