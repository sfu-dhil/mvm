<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Content;
use App\Repository\ContentRepository;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EmbeddedFilterTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * ManuscriptFilterType form.
 */
class ManuscriptContentFilterType extends AbstractType implements EmbeddedFilterTypeInterface {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('content', Filters\EntityFilterType::class, [
            'class' => Content::class,
            'multiple' => true,
            'label' => 'Poems in Manuscript',
            'query_builder' => fn (ContentRepository $repo) => $repo->createQueryBuilder('u')
                ->orderBy('u.firstLine', 'ASC'),
            'row_attr' => ['class' => 'mb-0'],
        ]);
    }

    public function getParent() {
        return Filters\SharedableFilterType::class; // this allow us to use the "add_shared" option
    }

    public function getBlockPrefix() {
        return 'content_filter';
    }
}
