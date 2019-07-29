<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ManuscriptType form.
 */
class ManuscriptType extends AbstractType
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
                $builder->add('description', null, array(
            'label' => 'Description',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('blankPageCount', null, array(
            'label' => 'Blank Page Count',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('filledPageCount', null, array(
            'label' => 'Filled Page Count',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('itemCount', null, array(
            'label' => 'Item Count',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('poemCount', null, array(
            'label' => 'Poem Count',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                $builder->add('additionalGenres', null, array(
            'label' => 'Additional Genres',
            'required' => true,
            'attr' => array(
                'help_block' => '',
            ),
        ));
                        $builder->add('place');
                        $builder->add('period');
                        $builder->add('archiveSource');
                        $builder->add('printSources');
                        $builder->add('themes');
        
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
            'data_class' => 'AppBundle\Entity\Manuscript'
        ));
    }

}
