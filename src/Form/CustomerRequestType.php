<?php

namespace App\Form;

use App\Entity\CustomerRequest;
use App\EventSubscriber\Form\CustomerRequestTypeSubscriber;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', null, ['required' => true])
            ->add('description')
            ->add('evaluationCriterias', CollectionType::class,
                [
                    'entry_type'   => EvaluationCriteriaType::class,
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ])
            ->add('submit', SubmitType::class);

        $builder->addEventSubscriber(new CustomerRequestTypeSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomerRequest::class,
        ]);
    }
}
