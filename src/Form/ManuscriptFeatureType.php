<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Feature;
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
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('feature', Select2EntityType::class, [
            'label' => 'Feature',
            'multiple' => false,
            'remote_route' => 'feature_typeahead',
            'class' => Feature::class,
            'required' => true,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'feature_new_popup',
                'add_label' => 'Add Feature',
            ],
        ]);
        $builder->add('note', null, [
            'label' => 'Note',
            'required' => true,
            'attr' => [
                'help_block' => '',
                'class' => 'tinymce small',
            ],
        ]);
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\ManuscriptFeature',
        ]);
    }
}
