<?php

namespace AppBundle\Form;

use AppBundle\Entity\Person;
use AppBundle\Entity\Region;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * PersonType form.
 */
class PersonType extends AbstractType {

    /**
     * Add form fields to $builder.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('anonymous', CheckboxType::class, array(
            'label'    => 'Anonymous',
            'required' => false,
            'attr'     => array(
                'help_block' => 'Is the person anonymous?',
            ),
        ));

        $builder->add('fullName', null, array(
            'label'    => 'Full Name',
            'required' => true,
            'attr'     => array(
                'help_block' => 'A canonical name for a person if known, or a descriptive identifier. Do not include square brackets.',
            ),
        ));
        $builder->add('variantNames', null, array(
            'label' => 'Variant Names',
            'allow_add'     => true,
            'allow_delete'  => true,
            'prototype'     => true,
            'entry_type'    => TextType::class,
            'entry_options' => array(
                'label' => false,
            ),
            'required'      => false,
            'attr'          => array(
                'help_block' => '',
                'class'      => 'collection collection-simple',
            ),
        ));

        $builder->add('sortableName', null, array(
            'label'    => 'Sortable Name',
            'required' => true,
            'attr'     => array(
                'help_block' => '',
            ),
        ));

        $builder->add('gender', ChoiceType::class, array(
            'expanded' => true,
            'multiple' => false,
            'required' => false,
            'choices' => array(
                'Female' => Person::FEMALE,
                'Male' => Person::MALE,
                'Unknown' => '',
            ),
            'preferred_choices' => [Person::FEMALE, Person::MALE]
        ));

        $builder->add('description', null, array(
            'label'    => 'Description',
            'required' => false,
            'attr'     => array(
                'help_block' => '',
                'class'      => 'tinymce',
            ),
        ));

        $builder->add('birthDate', TextType::class, array(
            'label'    => 'Birth Date',
            'required' => false,
            'attr'     => array(
                'help_block' => 'A four digit year, if known for certain. Uncertain date ranges (1901-1903) and circa dates (c1902) are supported here',
            ),
        ));
        $builder->add('deathDate', TextType::class, array(
            'label'    => 'Death Date',
            'required' => false,
            'attr'     => array(
                'help_block' => 'A four digit year, if known for certain. Uncertain date ranges (1901-1903) and circa dates (c1902) are supported here',
            ),
        ));
        $builder->add('birthRegion', Select2EntityType::class, array(
            'label'        => 'Birth Region',
            'multiple'     => false,
            'remote_route' => 'region_typeahead',
            'class'        => Region::class,
            'required'     => false,
            'allow_clear'  => true,
            'attr'         => array(
                'add_path'  => 'region_new_popup',
                'add_label' => 'Add Region',
            ),
        ));
        $builder->add('deathRegion', Select2EntityType::class, array(
            'label'        => 'Death Region',
            'multiple'     => false,
            'remote_route' => 'region_typeahead',
            'class'        => Region::class,
            'required'     => false,
            'allow_clear'  => true,
            'attr'         => array(
                'add_path'  => 'region_new_popup',
                'add_label' => 'Add Region',
            ),
        ));
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Person',
        ));
    }

}
