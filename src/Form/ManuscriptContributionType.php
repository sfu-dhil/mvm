<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\ManuscriptContribution;
use App\Entity\ManuscriptRole;
use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * ManuscriptContributionType form.
 */
class ManuscriptContributionType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
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
        $builder->add('role', Select2EntityType::class, [
            'label' => 'Manuscript Role',
            'multiple' => false,
            'remote_route' => 'manuscript_role_typeahead',
            'class' => ManuscriptRole::class,
            'required' => true,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'manuscript_role_new',
                'add_label' => 'Add Role',
            ],
            'placeholder' => 'Search for an existing role source by name',
        ]);
        $builder->add('note', null, [
            'label' => 'Note',
            'required' => true,
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
            'data_class' => ManuscriptContribution::class,
        ]);
    }
}
