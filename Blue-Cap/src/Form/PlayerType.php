<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;



class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudoPlayer')
            ->add('firstNamePlayer')
            ->add('lastNamePlayer')
            ->add('datePlayer', DateType::class, [
                    'widget' => 'single_text',
                ])
            ->add('descriptionPlayer')
            ->add('rolePlayer')
            ->add('imagePlayer', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                new File([
                'maxSize' => '1024k',
                'mimeTypes' => [
                'image/*',
                ],
               'mimeTypesMessage' => 'Veuillez entrer un format de document valide',
                ])
                ],
                ])
        
                ->add('game', EntityType::class, [
                    'class' => Game::class,
                    'choice_label' => 'nameGame',
                    ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
