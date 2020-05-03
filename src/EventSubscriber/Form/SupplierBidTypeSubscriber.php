<?php
/**
 * @package Mysupply_App
 * @author  Stenik Symfony Team <symfony@stenik.bg>
 */

namespace App\EventSubscriber\Form;

use App\Entity\EvaluationScore;
use App\Entity\SupplierProposal;
use App\Form\EvaluationScoreType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\EventSubscriber\Container;

/**
 * Class SupplierBidTypeSubscriber
 * @package App\EventSubscriber
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */
class SupplierBidTypeSubscriber extends Container implements EventSubscriberInterface
{
    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT   => 'onPreSubmit',
        ];
    }

    /**
     * @return SupplierProposal|null
     * @TODO: Move in Service
     */
    protected function getSipplierProposal()
    {
        $proposalId = $this->locator->get('request_stack')->getMasterRequest()->query->get('proposalId');
        if (null === $proposalId) {
            throw new \RuntimeException('Missing requestId parameter!');
        }
        $em          = $this->locator->get('doctrine.orm.entity_manager');
        $proposalRepo = $em->getRepository(SupplierProposal::class);

        if (!$supplierProposal = $proposalRepo->find($proposalId)) {
            throw new NotFoundHttpException(sprintf('Missing record with %s id!', $proposalId));
        }

        return $supplierProposal;
    }

    public function onPreSubmit(FormEvent $event)
    {
        $supplierProposal = $this->getSipplierProposal();
        $data            = $event->getForm()->getViewData();
        $data->setSupplierProposal($supplierProposal);
    }
}
