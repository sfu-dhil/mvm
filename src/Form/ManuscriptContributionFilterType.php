<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EmbeddedFilterTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * ManuscriptFilterType form.
 */
class ManuscriptContributionFilterType extends AbstractType implements EmbeddedFilterTypeInterface {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('person', Filters\EntityFilterType::class, [
            'class' => Person::class,
            'multiple' => true,
            'label' => 'Manuscript Contributors',
            'query_builder' => fn (PersonRepository $repo) => $repo->createQueryBuilder('u')
                ->orderBy('u.sortableName', 'ASC'),
            'row_attr' => ['class' => 'mb-0'],
        ]);
    }

    public function getParent() {
        return Filters\SharedableFilterType::class; // this allow us to use the "add_shared" option
    }

    public function getBlockPrefix() {
        return 'contribution_filter';
    }
}
