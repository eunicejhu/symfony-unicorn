<?php

namespace App\Form;

use App\Services\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, ['attr' => ['placeholder' => 'email'], 'required'=>false])
            ->add('firstname', TextType::class,  ['attr' => ['placeholder' => 'firstname'],'required'=>false])
            ->add('lastname', TextType::class,  ['attr' => ['placeholder' => 'lastname'],'required'=>false])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn-primary w-100', 'type'=>'submit']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Search::class
        ]);
    }
}
