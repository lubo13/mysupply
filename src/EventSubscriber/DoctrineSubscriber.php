<?php
/**
 * @package Mysupply_App
 * @author  Stenik Symfony Team <symfony@stenik.bg>
 */

namespace App\EventSubscriber;

use App\Entity\CustomUserInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Description of DoctrineSubscriber
 *
 * @author 13
 */
class DoctrineSubscriber extends Container implements EventSubscriber
{
    /**
     * @return array|string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }

    /**
     * @param \Doctrine\Persistence\Event\LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof CustomUserInterface && $user = $this->getUser()) {
            $entity->setUser($user);
        }
    }

}
