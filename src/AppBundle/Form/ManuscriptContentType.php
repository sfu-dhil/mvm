<?php

namespace AppBundle\Form;

use AppBundle\Entity\Content;
use AppBundle\Entity\PrintSource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * ManuscriptContentType form.
 */
class ManuscriptContentType extends AbstractType {

    /**
     * Add form fields to $builder.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('content', Select2EntityType::class, array(
            'label' => 'Content',
            'multiple' => false,
            'remote_route' => 'content_typeahead',
            'class' => Content::class,
            'required' => true,
            'allow_clear' => true,
            'attr' => array(
                'add_path' => 'content_new_popup',
                'add_label' => 'Add Content',
            ),
        ));

        $builder->add('printSource', Select2EntityType::class, array(
            'label' => 'Print Source',
            'multiple' => false,
            'remote_route' => 'print_source_typeahead',
            'class' => PrintSource::class,
            'required' => true,
            'allow_clear' => true,
            'attr' => array(
                'add_path' => 'print_source_new_popup',
                'add_label' => 'Add Print Source',
            ),
        ));

        $builder->add('context', null, array(
            'label'    => 'Context',
            'required' => false,
            'attr'     => array(
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
            'data_class' => 'AppBundle\Entity\ManuscriptContent',
        ));
    }

}
