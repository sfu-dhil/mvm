<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Manuscript;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ManuscriptContentType form.
 */
class ManuscriptContentsType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('manuscript_contents', CollectionType::class, [
            'label' => 'Contents',
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'entry_type' => ManuscriptContentType::class,
            'entry_options' => [
                'label' => false,
            ],
            'required' => true,
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
