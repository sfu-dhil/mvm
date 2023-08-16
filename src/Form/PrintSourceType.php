<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\PrintSource;
use App\Entity\Region;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * PrintSourceType form.
 */
class PrintSourceType extends ArchiveType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        parent::buildForm($builder, $options);
        $builder->add('regions', Select2EntityType::class, [
            'label' => 'Regions',
            'multiple' => true,
            'remote_route' => 'region_typeahead',
            'class' => Region::class,
            'required' => false,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'region_new',
                'add_label' => 'Add Region',
            ],
            'placeholder' => 'Search for an existing region by name',
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
            'data_class' => PrintSource::class,
        ]);
    }
}
