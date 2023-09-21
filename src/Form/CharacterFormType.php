<?php

namespace App\Form;

use App\Entity\Characters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CharacterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => [
                    'placeholder' => 'Enter the name of the character',
                    'class' => 'form-control',
                ]
            ])
            ->add('mass', TextType::class, [
                'label' => 'Mass',
                'attr' => [
                    'placeholder' => 'Enter the mass of the character',
                    'class' => 'form-control',
                ]
            ])
            ->add('height', TextType::class, [
                'label' => 'Height',
                'attr' => [
                    'placeholder' => 'Enter the height of the character',
                    'class' => 'form-control',
                ]
            ])
            ->add('gender', TextType::class, [
                'label' => 'Gnder',
                'attr' => [
                    'placeholder' => 'Enter the gender of the character',
                    'class' => 'form-control',
                ]
            ])
            ->add('picture', FileType::class, [
                'label' => 'Picture',
                'attr' => [
                    'placeholder' => 'Enter the picture of the character',
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp',

                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Characters::class,
        ]);
    }
}
