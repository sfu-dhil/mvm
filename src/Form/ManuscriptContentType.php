<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form;

use App\Entity\Content;
use App\Entity\PrintSource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * ManuscriptContentType form.
 */
class ManuscriptContentType extends AbstractType {
    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('content', Select2EntityType::class, [
            'label' => 'Content',
            'multiple' => false,
            'remote_route' => 'content_typeahead',
            'class' => Content::class,
            'required' => true,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'content_new_popup',
                'add_label' => 'Add Content',
            ],
        ]);

        $builder->add('printSource', Select2EntityType::class, [
            'label' => 'Print Source',
            'multiple' => false,
            'remote_route' => 'print_source_typeahead',
            'class' => PrintSource::class,
            'required' => true,
            'allow_clear' => true,
            'attr' => [
                'add_path' => 'print_source_new_popup',
                'add_label' => 'Add Print Source',
            ],
        ]);

        $builder->add('context', null, [
            'label' => 'Context',
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
            'data_class' => 'App\Entity\ManuscriptContent',
        ]);
    }
}
