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
                    'aucun' => 'none',
                    'ARTICLES' => [
                        'Un article' => 'One_article',
                        'Plusieurs articles' => 'More_article',
                        'Une categorie' => 'Category',
                    ],
                    'EVENEMENTS' =>[
                        'Un évènement' => 'One_event',
                        'les évènements' => 'Events',
                        'historiques des évènements' => 'HistoryOfEvent',
                    ],
                    'GALERIES' =>[
                        "image seule" => "Media_one",
                    ],
                    'MEMBRES' => [
                        'membre' => 'Member',
                        "bulletin d'adhésion" => "Adhesion",
                    ],
                    'ANIMATION' => [
                        'Compteur' => "CountUp"
                    ],
                    'DIVERS' => [
                        'introduction' => 'intro',
                        "liste des avis" => "Avis",
                        'Autres' => 'Others'
                    ],
                ],
            ])
            ->add('favorites')
            ->add('fluid')
            ->add('position')
            ->add('isShowtitle')
            ->add('isShowdescription')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
        ]);
    }
}
