<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * PersonType form.
 */
class PersonTypeaheadType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('person', EntityType::class, [
            'label' => 'Contributor',
            'multiple' => false,
            'class' => Person::class,
            'required' => false,
            'placeholder' => 'Search for an existing person by name',
            'attr' => [
                'class' => 'select2',
                'data-theme' => 'bootstrap-5',
            ],
        ]);
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
