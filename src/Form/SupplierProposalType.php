<?php

namespace App\Form;

use App\Entity\SupplierProposal;
use App\EventSubscriber\Form\SupplierProposalTypeSubscriber;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class SupplierProposalType extends AbstractType implements ServiceSubscriberInterface
{
    private $locator;

    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
    }

    public static function getSubscribedServices()
    {
        return [
            SupplierProposalTypeSubscriber::class => SupplierProposalTypeSubscriber::class,
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', null, ['help' => 'Below you should add you proposals for criterion']);
        $builder->addEventSubscriber($this->locator->get(SupplierProposalTypeSubscriber::class));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SupplierProposal::class,
        ]);
    }
}
