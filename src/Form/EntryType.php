<?php

namespace App\Form;

use App\Entity\Entry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            // ->add('date')
            // ->add('created_at')
            // ->add('updated_at')
            // ->add('user')
        ;

        // ->add('date', HiddenType::class, ['attr' => ['class' => 'd-none']])

        // ->add('ajaxString', HiddenType::class, [
        //     'mapped' => false,
        //     'attr' => ['class' => 'hidden-field', 'value' => $secretString]
        // ])
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entry::class,
        ]);
    }
}
