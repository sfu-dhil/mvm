<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Coterie;
use App\Entity\Manuscript;
use App\Entity\Person;
use App\Entity\Region;
use Nines\UtilBundle\Form\TermType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Coterie form.
 */
class CoterieType extends TermType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        parent::buildForm($builder, $options);
        $builder->add('people', Select2EntityType::class, [
            'label' => 'People',
            'multiple' => true,
            'remote_route' => 'person_typeahead',
            'class' => Person::class,
            'required' => false,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'person_new_popup',
                'add_label' => 'Add Person',
            ],
        ]);
        $builder->add('manuscripts', Select2EntityType::class, [
            'label' => 'Manuscripts',
            'multiple' => true,
            'remote_route' => 'manuscript_typeahead',
            'class' => Manuscript::class,
            'required' => false,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'manuscript_new_popup',
                'add_label' => 'Add Manuscript',
            ],
        ]);
        $builder->add('regions', Select2EntityType::class, [
            'label' => 'Regions',
            'multiple' => true,
            'remote_route' => 'region_typeahead',
            'class' => Region::class,
            'required' => false,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'region_new_popup',
                'add_label' => 'Add Region',
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
            'data_class' => Coterie::class,
        ]);
    }
}
