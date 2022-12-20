<?php

namespace App\Form\Webapp;

use App\Entity\Webapp\Section;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('page')
            ->add('content', ChoiceType::class, [
                'choices'  => [
                    'AUCUN' => 'none',
                    'ARTICLES' => [
                        'Un article' => 'ONE_ARTICLE',
                        'Un article complet' => 'ONE_ARTICLE_COMPLETE',
                        'Les 5 derniers articles' => 'FIVE_ARTICLES',
                        'Une categorie' => 'Category',
                    ],
                    'Ressources' =>[
                        'Toutes les ressources' => 'ALL_RESSOURCES',
                        'Une catégorie'=> 'ONE_RESSOURCE_CAT',
                        'Une ressources' => 'ONE_RESSOURCES',
                    ],
                    'Collèges' =>[
                        "un collège" => "ONE_COLLEGE",
                        "tous les collèges" => 'ALL_COLLEGES'
                    ],
                    'DIVERS' => [
                        'Autres' => 'OTHER_CONTENT',
                    ],
                ],
            ])
            ->add('favorites')
            ->add('fluid')
            ->add('position')
            ->add('isShowTitle')
            ->add('isShowdescription')
            ->add('category')
            ->add('ressourcesCat')
            ->add('oneArticle')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
        ]);
    }
}
