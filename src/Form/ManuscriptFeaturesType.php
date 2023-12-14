<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Manuscript;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ManuscriptFeatureType form.
 */
class ManuscriptFeaturesType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('manuscript_features', CollectionType::class, [
            'label' => 'Features',
            'required' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => ManuscriptFeatureType::class,
            'entry_options' => [
                'label' => false,
            ],
            'prototype' => true,
            'by_reference' => false,
            'attr' => [
                'class' => 'collection collection-complex',
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
            'data_class' => Manuscript::class,
        ]);
    }
}
