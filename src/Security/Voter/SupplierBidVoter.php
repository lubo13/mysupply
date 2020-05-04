<?php
/**
 * @package Mysupply_App
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */

namespace App\Security\Voter;

use App\Entity\CustomerRequest;
use App\Entity\SupplierBid;
use App\Entity\SupplierBidInterface;
use App\Entity\SupplierProposal;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SupplierBidVoter
 * @package App\Security\Voter
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */
class SupplierBidVoter extends AbstractEntityVoter
{
    const ALLOWED_FIELDS_XML = ['accepted'];

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

        if ($subject instanceof SupplierBidInterface) {
            return true;
        } elseif ($subject === SupplierBid::class) {
            return true;
        } elseif ($subject instanceof \ReflectionClass && $subject->implementsInterface(SupplierBidInterface::class)) {
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
        if ($this->checkAuthForXmlCreate($customerRequest, $user, $attribute)) {
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
    protected function canList($customerRequest, UserInterface $user, $attribute)
    {
        return false;
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
        return false;
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
        if ($this->checkAuthForXmlEdit($customerRequest, $user, $attribute)) {
            return true;
        }

        return false;
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
        return false;
    }

    /**
     * @param                                                     $customerRequestClass
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param                                                     $attribute
     *
     * @return bool
     */
    protected function checkAuthForXmlCreate($supplierBidClass, UserInterface $user, $attribute)
    {
        $masterRequest = $this->locator->get('request_stack')->getMasterRequest();
        $proposalId    = $masterRequest->query->get('proposalId');

        $entityManager = $this->locator->get('doctrine.orm.entity_manager');

        $supplierProposal = $entityManager->getRepository(SupplierProposal::class)->find($proposalId);

        if ($supplierProposal && $supplierProposal->getUser() === $user) {
            return true;
        }

        return false;
    }

    /**
     * @param                                                     $supplierBidClass
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param                                                     $attribute
     *
     * @return bool
     */
    protected function checkAuthForXmlEdit($supplierBidClass, UserInterface $user, $attribute)
    {
        $masterRequest = $this->locator->get('request_stack')->getMasterRequest();
        $supplierBidId = $masterRequest->attributes->get('id');
        $propery       = $masterRequest->query->get('property');

        if (!$masterRequest->isXmlHttpRequest() || null === $supplierBidId || !in_array($propery,
                self::ALLOWED_FIELDS_XML)) {
            return false;
        }

        $entityManager = $this->locator->get('doctrine.orm.entity_manager');
        $supplierBid   = $entityManager->getRepository(SupplierBid::class)->find($supplierBidId);

        if (null === $supplierBid || !($supplierProposal = $supplierBid->getSupplierProposal()) || $supplierProposal->getCustomerRequest()->getUser() !== $user) {
            return false;
        }

        return true;
    }

}
