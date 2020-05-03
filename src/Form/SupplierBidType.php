<?php

namespace App\Form;

use App\Entity\SupplierBid;
use App\EventSubscriber\Form\SupplierBidTypeSubscriber;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class SupplierBidType extends AbstractType implements ServiceSubscriberInterface
{
    private $locator;

    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
    }

    public static function getSubscribedServices()
    {
        return [
            SupplierBidTypeSubscriber::class => SupplierBidTypeSubscriber::class,
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contracts')
            ->add('price');
        $builder->addEventSubscriber($this->locator->get(SupplierBidTypeSubscriber::class));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SupplierBid::class,
        ]);
    }
}
