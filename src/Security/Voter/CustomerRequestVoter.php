<?php
/**
 * @package Mysupply_App
 * @author  Stenik Symfony Team <symfony@stenik.bg>
 */

namespace App\Security\Voter;

use App\Entity\CustomerRequest;
use App\Entity\CustomerRequestInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class CustomerRequestVoter
 * @package App\Security\Voter
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */
class CustomerRequestVoter extends AbstractEntityVoter
{
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

        if ($subject instanceof CustomerRequestInterface) {
            return true;
        } elseif ($subject === CustomerRequest::class) {
            return true;
        } elseif ($subject instanceof \ReflectionClass && $subject->implementsInterface(CustomerRequestInterface::class)) {
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
        return $this->locator->get('security')->isGranted('ROLE_CUSTOMER');
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
        return $this->locator->get('security')->isGranted('ROLE_CUSTOMER');
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
        return $this->locator->get('security')->isGranted('ROLE_CUSTOMER');
    }

    /**
     * @param                                                     $customerRequest
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @param                                                     $attribute
     *
     * @return bool|mixed
     */
    protected function canEdit($customerRequest, UserInterface $user, $attribute)
    {
        if (!$this->locator->get('security')->isGranted('ROLE_CUSTOMER') || !$this->checkAuth($customerRequest,
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
     * @return bool|mixed
     */
    protected function canDelete($customerRequest, UserInterface $user, $attribute)
    {
        if (!$this->locator->get('security')->isGranted('ROLE_CUSTOMER') || !$this->checkAuth($customerRequest,
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
        $request = $this->locator->get('request_stack')->getMasterRequest();
        $id      = $request->query->get('id');
        if (null === $id) {
            return false;
        }

        $entityManager = $this->locator->get('doctrine.orm.entity_manager');

        $customerRequest = $entityManager->getRepository(CustomerRequest::class)->find($id);
        if ($customerRequest->getUser() !== $user || $customerRequest->getSupplierProposals()->count() > 0) {
            return false;
        }

        return true;
    }

}
