<?php

namespace AppBundle\Form;

use AppBundle\Entity\Manuscript;
use AppBundle\Entity\PrintSource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * ContentType form.
 */
class ContentType extends AbstractType
{
    /**
     * Add form fields to $builder.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        $builder->add('title', null, array(
            'label' => 'Title',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('transcription', null, array(
            'label' => 'Transcription',
            'required' => false,
            'attr' => array(
                'help_block' => '',
                'class' => 'tinymce',
            ),
        ));
                $builder->add('context', null, array(
            'label' => 'Context',
            'required' => false,
            'attr' => array(
                'help_block' => '',
                'class' => 'tinymce',
            ),
        ));
                $builder->add('description', null, array(
            'label' => 'Description',
            'required' => false,
            'attr' => array(
                'help_block' => '',
                'class' => 'tinymce',
            ),
        ));
        $builder->add('manuscript', Select2EntityType::class, array(
            'label' => 'Manuscript',
            'multiple' => false,
            'remote_route' => 'manuscript_typeahead',
            'class' => Manuscript::class,
            'required' => true,
            'allow_clear' => true,
        ));
        $builder->add('printSource', Select2EntityType::class, array(
            'label' => 'Print Source',
            'multiple' => false,
            'remote_route' => 'print_source_typeahead',
            'class' => PrintSource::class,
            'required' => true,
            'allow_clear' => true,
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Content'
        ));
    }

}
