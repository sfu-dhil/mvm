<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\ContentContribution;
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
                'add_path' => 'content_role_new',
                'add_label' => 'Add Role',
            ],
            'placeholder' => 'Search for an existing role by name',
        ]);
        $builder->add('person', Select2EntityType::class, [
            'label' => 'Contributor',
            'multiple' => false,
            'remote_route' => 'person_typeahead',
            'class' => Person::class,
            'required' => true,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'person_new',
                'add_label' => 'Add Person',
            ],
            'placeholder' => 'Search for an existing person by name',
        ]);
        $builder->add('note', null, [
            'label' => 'Note',
            'required' => false,
            'attr' => [
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
            'data_class' => ContentContribution::class,
        ]);
    }
}
