<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Person;
use Nines\MediaBundle\Form\LinkableType;
use Nines\MediaBundle\Form\Mapper\LinkableMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * PersonType form.
 */
class PersonType extends AbstractType {
    public function __construct(
        public LinkableMapper $mapper,
    ) {
    }

    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('anonymous', CheckboxType::class, [
            'label' => 'Anonymous',
            'required' => false,
            'help' => 'Is the person anonymous?',
        ]);

        $builder->add('fullName', null, [
            'label' => 'Full Name',
            'required' => true,
            'help' => 'A canonical name for a person if known, or a descriptive identifier. Do not include square brackets.',
        ]);
        $builder->add('variantNames', null, [
            'label' => 'Variant Names',
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'entry_type' => TextType::class,
            'entry_options' => [
                'label' => false,
            ],
            'required' => false,
            'attr' => [
                'class' => 'collection collection-simple',
            ],
        ]);

        $builder->add('sortableName', null, [
            'label' => 'Sortable Name',
            'required' => true,
        ]);

        $builder->add('gender', ChoiceType::class, [
            'expanded' => true,
            'multiple' => false,
            'required' => false,
            'choices' => [
                'Female' => Person::FEMALE,
                'Male' => Person::MALE,
                'Unknown' => '',
            ],
            'preferred_choices' => [Person::FEMALE, Person::MALE],
        ]);

        $builder->add('description', null, [
            'label' => 'Description',
            'required' => false,
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);

        $builder->add('birthDate', TextType::class, [
            'label' => 'Birth Date',
            'required' => false,
            'help' => 'A four digit year, if known for certain. Uncertain date ranges (1901-1903) and circa dates (c1902) are supported here',
        ]);
        $builder->add('deathDate', TextType::class, [
            'label' => 'Death Date',
            'required' => false,
            'help' => 'A four digit year, if known for certain. Uncertain date ranges (1901-1903) and circa dates (c1902) are supported here',
        ]);
        LinkableType::add($builder, $options);
        $builder->setDataMapper($this->mapper);
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
