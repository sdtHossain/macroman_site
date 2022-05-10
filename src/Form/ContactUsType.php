<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactUsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fname', TextType::class, [
                'required' => true,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'First Name'
                ]
            ])
            ->add('lname', TextType::class, [
                'required' => true,
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Last Name'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Email'
                ]
            ])
            ->add('website', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Website'
                ]
            ])
            ->add('message', TextareaType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Message'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Contact Now',
                'row_attr' => [
                    'class' => 'mt-4',
                ],
                'attr' => [
                    'class' => 'btn-primary text-white fw-600',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
