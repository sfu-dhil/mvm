<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\ContentRole;
use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * ContentContributionType form.
 */
class ContentContributionType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('role', Select2EntityType::class, [
            'label' => 'Content Role',
            'multiple' => false,
            'remote_route' => 'content_role_typeahead',
            'class' => ContentRole::class,
            'required' => true,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'content_role_new_popup',
                'add_label' => 'Add Role',
            ],
        ]);
        $builder->add('person', Select2EntityType::class, [
            'label' => 'Contributor',
            'multiple' => false,
            'remote_route' => 'person_typeahead',
            'class' => Person::class,
            'required' => true,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'person_new_popup',
                'add_label' => 'Add Person',
            ],
        ]);
        $builder->add('note', null, [
            'label' => 'Note',
            'required' => false,
            'attr' => [
                'help_block' => '',
                'class' => 'tinymce',
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
            'data_class' => 'App\Entity\ContentContribution',
        ]);
    }
}
