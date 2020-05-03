<?php
/**
 * @package Mysupply_App
 * @author  Stenik Symfony Team <symfony@stenik.bg>
 */

namespace App\EventSubscriber\Form;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class CustomerRequestTypeSubscriber
 * @package App\EventSubscriber
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */
class CustomerRequestTypeSubscriber implements EventSubscriberInterface
{
    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }


    /**
     * @param \Symfony\Component\Form\FormEvent $event
     */
    public function onPreSubmit(FormEvent $event)
    {
        $customerRequest = $event->getData();
        if (!$customerRequest) {
            return;
        }

        $addPrice = true;
        if (isset($customerRequest['evaluationCriterias'])) {
            foreach ($customerRequest['evaluationCriterias'] as $evaluationCriteria) {
                if (isset($evaluationCriteria['name']) &&  mb_strtolower($evaluationCriteria['name']) ==='price') {
                    $addPrice = false;
                }
            }
        }

        if ($addPrice) {
            $customerRequest['evaluationCriterias'][] = [
                'name'   => 'price',
                'weight' => 0
            ];
        }

        $event->setData($customerRequest);
    }
}
