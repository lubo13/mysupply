<?php
/**
 * @package Mysupply_App
 * @author  Stenik Symfony Team <symfony@stenik.bg>
 */

namespace App\EventSubscriber;

use App\Entity\CustomerRequestInterface;
use App\Utils\CustomerRequest;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use EasyCorp\Bundle\EasyAdminBundle\Exception\NoPermissionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

/**
 * Description of EasyAdminShowSubscriber
 *
 * @author 13
 */
class EasyAdminShowSubscriber implements EventSubscriberInterface, ServiceSubscriberInterface
{
    protected $locator;

    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
    }

    public static function getSubscribedServices()
    {
        return [
            CustomerRequest::class => CustomerRequest::class,
        ];
    }

    /**
     * @return array|\string[][]
     */
    public static function getSubscribedEvents()
    {
        return [
            EasyAdminEvents::POST_SHOW => ['onPostShow'],
        ];
    }

    /**
     * @param \Symfony\Component\EventDispatcher\GenericEvent $event
     *
     */
    public function onPostShow(GenericEvent $event)
    {
        $entity = $event->getArgument('entity');
        if ($entity instanceof CustomerRequestInterface) {
            $this->locator->get(CustomerRequest::class)->orderSupplierProposal($entity);
        }
    }

}
