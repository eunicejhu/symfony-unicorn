<?php

namespace App\Form;

use App\Services\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchEmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('keyword', SearchType::class, ['attr' => ['placeholder' => 'Search by Email, Firstname or Lastname', 'class' => 'form-control', 'aria-label' => 'Search', 'autofocus' => true], 'required' => true])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn-primary', 'type' => 'submit']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Search::class,
        ]);
    }
}
