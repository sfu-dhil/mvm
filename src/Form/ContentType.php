<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Content;
use Nines\MediaBundle\Form\LinkableType;
use Nines\MediaBundle\Form\Mapper\LinkableMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ContentType form.
 */
class ContentType extends AbstractType {
    public function __construct(
        public LinkableMapper $mapper,
    ) {
    }

    /**
     * Add form fields to $builder.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('firstLine', null, [
            'label' => 'First Line',
            'required' => true,
        ]);
        $builder->add('title', null, [
            'label' => 'Title',
            'required' => false,
        ]);
        $builder->add('date', TextType::class, [
            'label' => 'Date',
            'required' => false,
            'help' => 'A four digit year, if known for certain. Uncertain date ranges (1901-1903) and circa dates (c1902) are supported here.',
        ]);
        $builder->add('transcription', null, [
            'label' => 'Transcription',
            'required' => false,
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);
        $builder->add('description', null, [
            'label' => 'Description',
            'required' => false,
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);
        LinkableType::add($builder, $options);
        $builder->setDataMapper($this->mapper);
    }

    /**
     * Define options for the form.
     *
     * Set default, optional, and required options passed to the
     * buildForm() method via the $options parameter.
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Content::class,
        ]);
    }
}
