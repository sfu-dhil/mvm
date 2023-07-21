<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Feature;
use App\Entity\ManuscriptFeature;
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
                'add_path' => 'feature_new',
                'add_label' => 'Add Feature',
            ],
        ]);
        $builder->add('note', null, [
            'label' => 'Note',
            'required' => true,
            'attr' => [
                'class' => 'tinymce small',
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
            'data_class' => ManuscriptFeature::class,
        ]);
    }
}
