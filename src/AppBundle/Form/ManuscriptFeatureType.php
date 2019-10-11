<?php

namespace AppBundle\Form;

use AppBundle\Entity\Feature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * ManuscriptFeatureType form.
 */
class ManuscriptFeatureType extends AbstractType {
    /**
     * Add form fields to $builder.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('feature', Select2EntityType::class, array(
            'label' => 'Feature',
            'multiple' => false,
            'remote_route' => 'feature_typeahead',
            'class' => Feature::class,
            'required' => true,
            'allow_clear' => true,
            'attr' => array(
                'add_path' => 'feature_new_popup',
                'add_label' => 'Add Feature',
            ),
        ));
        $builder->add('note', null, array(
            'label' => 'Note',
            'required' => true,
            'attr' => array(
                'help_block' => '',
                'class' => 'tinymce small',
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
            'data_class' => 'AppBundle\Entity\ManuscriptFeature',
        ));
    }
}
