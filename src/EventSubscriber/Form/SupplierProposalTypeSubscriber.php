<?php
/**
 * @package Mysupply_App
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */

namespace App\EventSubscriber\Form;

use App\Entity\CustomerRequest;
use App\Entity\EvaluationScore;
use App\Form\EvaluationScoreType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\EventSubscriber\Container;

/**
 * Class SupplierProposalTypeSubscriber
 * @package App\EventSubscriber
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */
class SupplierProposalTypeSubscriber extends Container implements EventSubscriberInterface
{
    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSet',
            FormEvents::PRE_SUBMIT   => 'onPreSubmit',
        ];
    }

    /**
     * @return CustomerRequest|null
     * @TODO: Move in Service
     */
    protected function getCustomerRequest()
    {
        $requestId = $this->locator->get('request_stack')->getMasterRequest()->query->get('requestId');
        if (null === $requestId) {
            throw new \RuntimeException('Missing requestId parameter!');
        }
        $em          = $this->locator->get('doctrine.orm.entity_manager');
        $requestRepo = $em->getRepository(CustomerRequest::class);

        if (!$customerRequest = $requestRepo->find($requestId)) {
            throw new NotFoundHttpException(sprintf('Missing record with %s id!', $requestId));
        }

        return $customerRequest;
    }


    public function onPreSet(FormEvent $event)
    {
        $customerRequest = $this->getCustomerRequest();

        $form = $event->getForm();

        $data = [];
        foreach ($customerRequest->getEvaluationCriterias() as $evaluationCriteria) {
            $evaluationScore = new EvaluationScore();
            $evaluationScore->setEvaluationCriteria($evaluationCriteria);
            $data[] = $evaluationScore;
        }

        $form->add('evaluationScores', CollectionType::class, [
            'entry_type'    => EvaluationScoreType::class,
            'label'         => false,
            'compound'      => true,
            'entry_options' => [],
            'data'          => $data
        ]);

        $form->add('submit', SubmitType::class);
    }

    public function onPreSubmit(FormEvent $event)
    {
        $customerRequest = $this->getCustomerRequest();
        $data            = $event->getForm()->getData();
        $data->setCustomerRequest($customerRequest);
    }
}
