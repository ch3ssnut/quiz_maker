<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class)
            ->add('image', FileType::class, [
                'required' => false,
                'label' => 'Image',
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M'
                    ])
                ]

                
            ])
            ->add('anwsers', CollectionType::class, [
                'label' => false,
                'entry_type' => TextType::class,
                'allow_add' => true,
                'prototype' => true,
                'required' => false,
                'delete_empty' => true,
                'entry_options' => [
                    'attr' => [
                        'placeholder' => 'Add anwser or leave empty',
                        'class' => 'form__input add-anwser'
                    ]
                ]
            ])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
