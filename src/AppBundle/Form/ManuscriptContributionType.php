<?php

namespace AppBundle\Form;

use AppBundle\Entity\ManuscriptRole;
use AppBundle\Entity\Person;
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
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('person', Select2EntityType::class, array(
            'label' => 'Contributor',
            'multiple' => false,
            'remote_route' => 'person_typeahead',
            'class' => Person::class,
            'required' => true,
            'allow_clear' => true,
            'attr' => array(
                'add_path' => 'person_new_popup',
                'add_label' => 'Add Person',
            ),
        ));
        $builder->add('role', Select2EntityType::class, array(
            'label' => 'Manuscript Role',
            'multiple' => false,
            'remote_route' => 'manuscript_role_typeahead',
            'class' => ManuscriptRole::class,
            'required' => true,
            'allow_clear' => true,
            'attr' => array(
                'add_path' => 'manuscript_role_new_popup',
                'add_label' => 'Add Role',
            ),
        ));
        $builder->add('note', null, array(
            'label' => 'Note',
            'required' => true,
            'attr' => array(
                'help_block' => '',
                'class' => 'tinymce',
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
            'data_class' => 'AppBundle\Entity\ManuscriptContribution',
        ));
    }
}
