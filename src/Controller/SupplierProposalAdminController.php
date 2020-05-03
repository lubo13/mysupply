<?php

namespace App\Controller;

use App\Entity\CustomerRequest;
use App\Entity\SupplierProposal;
use App\Form\SupplierBidType;
use App\Form\SupplierProposalType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\NoPermissionException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SupplierProposalAdminController extends EasyAdminController
{
    /**
     * @Route("/{_locale}/admin/proposal/xml/", name="proposal_xml_edit")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     *
     * @throws ForbiddenActionException
     */
    public function editXmlAction(Request $request, ValidatorInterface $validator)
    {
        $this->initialize($request);

        if (null === $request->query->get('entity')) {
            return $this->redirectToBackendHomepage();
        }

        $action = $request->query->get('action', 'list');
        if (!$this->isActionAllowed($action)) {
            throw new ForbiddenActionException(['action' => $action, 'entity_name' => $this->entity['name']]);
        }
        $property = $this->request->query->get('property');
        $newValue = $this->request->query->get('value');
        if (\in_array($action,
                ['edit']) || !$this->request->isXmlHttpRequest() || !$property || !$newValue) {
            $id                 = $this->request->query->get('id');
            $entity             = $this->request->attributes->get('easyadmin')['item'];
            $requiredPermission = $this->entity[$action]['item_permission'];
            $userHasPermission  = $this->get('easyadmin.security.authorization_checker')->isGranted($requiredPermission,
                $entity);
            if (false === $userHasPermission) {
                throw new NoPermissionException([
                    'action'      => $action,
                    'entity_name' => $this->entity['name'],
                    'entity_id'   => $id
                ]);
            }
        }

        $this->dispatch(EasyAdminEvents::PRE_EDIT);

        $entity->{'set' . $property}($newValue);
        $errors = $validator->validate($entity);

        if (count($errors) > 0) {
            $errorsString = '';
            foreach ($errors as $error) {
                $errorsString = ' ' . $error->getMessage();
            }
            return new JsonResponse($errorsString, 400);
        }

        $this->updateEntity($entity);
        $this->dispatch(EasyAdminEvents::POST_EDIT);
        return new JsonResponse((int)$newValue);
    }

    protected function createProposalNewForm(SupplierProposal $supplierProposal, $options)
    {
        return $this->get('form.factory')->createBuilder(SupplierProposalType::class, $supplierProposal)->getForm();
    }

    protected function createProposalEditForm(SupplierProposal $supplierProposal, $options)
    {
        return $this->get('form.factory')->createBuilder(SupplierProposalType::class, $supplierProposal)->getForm();
    }

    protected function createProposalListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter)
    {
        $categories = $this->getUser()->getCategories();

        return $this->get('doctrine')->getManagerForClass(CustomerRequest::class)->getRepository(CustomerRequest::class)->createListQueryBuilderForProposal($categories,
            $sortDirection, $sortField, $dqlFilter);
    }

    protected function renderProposalTemplate($actionName, $templatePath, array $parameters = [])
    {
        switch ($actionName) {
            case 'list':
                $templatePath         = 'CRUD/proposal_list.html.twig';
                $parameters['fields'] = $this->config['entities']['Request']['list']['fields'];
                break;
            case 'new':
                $templatePath                  = 'CRUD/proposal_new.html.twig';
                $parameters['customerRequest'] = $this->em->getRepository(CustomerRequest::class)->find($this->request->query->get('requestId'));
                break;
            case 'show':
                $templatePath          = 'CRUD/proposal_show.html.twig';
                $parameters['entity']  = $this->em->getRepository(CustomerRequest::class)->find($this->request->query->get('requestId'));
                $parameters['bidForm'] = $this->get('form.factory')->createBuilder(SupplierBidType::class)->getForm()->createView();
                break;
        }

        return $this->render($templatePath, $parameters);
    }
}
