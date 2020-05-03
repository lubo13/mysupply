<?php
/**
 * @package Mysupply_App
 * @author  Stenik Symfony Team <symfony@stenik.bg>
 */

namespace App\Security\Voter;

use App\Entity\CustomerRequest;
use App\Entity\SupplierProposal;
use App\Entity\SupplierProposalInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SupplierProposalVoter
 * @package App\Security\Voter
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */
class SupplierProposalVoter extends AbstractEntityVoter
{
    const ALLOWED_FIELDS_XML = ['points', 'accepted'];

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::CREATE, self::EDIT, self::LIST, self::DELETE, self::SHOW])) {
            return false;
        }

        if ($subject instanceof SupplierProposalInterface) {
            return true;
        } elseif ($subject === SupplierProposal::class) {
            return true;
        } elseif ($subject instanceof \ReflectionClass && $subject->implementsInterface(SupplierProposalInterface::class)) {
            return true;
        }

        return false;
    }

    /**
     * @param                                                     $customerRequest
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param                                                     $attribute
     *
     * @return mixed
     */
    protected function canCreate($customerRequest, UserInterface $user, $attribute)
    {
        if (!$this->locator->get('security')->isGranted('ROLE_SUPPLIER') || !$this->checkAuth($customerRequest,
                $user, $attribute)) {
            return false;
        }

        return true;
    }

    /**
     * @param                                                     $customerRequest
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param                                                     $attribute
     *
     * @return mixed
     */
    protected function canList($customerRequest, UserInterface $user, $attribute)
    {
        return $this->locator->get('security')->isGranted('ROLE_SUPPLIER');
    }

    /**
     * @param                                                     $customerRequest
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param                                                     $attribute
     *
     * @return mixed
     */
    protected function canShow($customerRequest, UserInterface $user, $attribute)
    {
        return $this->locator->get('security')->isGranted('ROLE_SUPPLIER');
    }

    /**
     * @param                                                     $customerRequest
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param                                                     $attribute
     *
     * @return bool
     */
    protected function canEdit($customerRequest, UserInterface $user, $attribute)
    {
        if ($this->checkAuthForXml($customerRequest, $user, $attribute)) {
            return true;
        }

        if (!$this->checkAuth($customerRequest, $user,
                $attribute) || !$this->locator->get('security')->isGranted('ROLE_SUPPLIER')) {
            return false;
        }

        return true;
    }

    /**
     * @param                                                     $customerRequest
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param                                                     $attribute
     *
     * @return bool
     */
    protected function canDelete($customerRequest, UserInterface $user, $attribute)
    {
        if (!$this->locator->get('security')->isGranted('ROLE_SUPPLIER') || !$this->checkAuth($customerRequest,
                $user, $attribute)) {
            return false;
        }

        return true;
    }

    /**
     * @param                                                     $customerRequestClass
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param                                                     $attribute
     *
     * @return bool
     */
    protected function checkAuth($customerRequestClass, UserInterface $user, $attribute)
    {
        $masterRequest = $this->locator->get('request_stack')->getMasterRequest();
        $requestId     = $masterRequest->query->get('requestId');
        $proposalId    = $masterRequest->query->get('id');
        if (null === $requestId) {
            return false;
        }

        $entityManager = $this->locator->get('doctrine.orm.entity_manager');

        $customerRequest  = $entityManager->getRepository(CustomerRequest::class)->find($requestId);
        $supplierProposal = null;
        if ($proposalId) {
            $supplierProposal = $entityManager->getRepository(SupplierProposal::class)->find($proposalId);
        }

        $userCategories = [];
        foreach ($user->getCategories() as $userCategory) {
            $userCategories[] = $userCategory->getId();
        }

        if (($supplierProposal && $supplierProposal->getUser() !== $user) || !$customerRequest || !in_array($customerRequest->getCategory()->getId(),
                $userCategories)) {
            return false;
        }

        return true;
    }

    /**
     * @param                                                     $customerRequestClass
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param                                                     $attribute
     *
     * @return bool
     */
    protected function checkAuthForXml($customerRequestClass, UserInterface $user, $attribute)
    {
        $masterRequest = $this->locator->get('request_stack')->getMasterRequest();
        $proposalId    = $masterRequest->query->get('id');
        $propery       = $masterRequest->query->get('property');

        if (!$masterRequest->isXmlHttpRequest() || null === $proposalId || !in_array($propery,
                self::ALLOWED_FIELDS_XML)) {
            return false;
        }
        $entityManager = $this->locator->get('doctrine.orm.entity_manager');
        $proposal      = $entityManager->getRepository(SupplierProposal::class)->find($proposalId);

        if (null === $proposal || !($evaluationScores = $proposal->getEvaluationScores()) || !($evaluationCriteria = $evaluationScores->get(0)->getEvaluationCriteria()) || $evaluationCriteria->getCustomerRequest()->getUser() !== $user) {
            return false;
        }

        return true;
    }

}
