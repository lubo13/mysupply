<?php

namespace App\Controller;

use App\Entity\SupplierBid;
use App\Entity\SupplierBidInterface;
use App\Form\SupplierBidType;
use App\Security\Voter\AbstractEntityVoter;
use EasyCorp\Bundle\EasyAdminBundle\Exception\NoPermissionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SupplierBidAdminController extends AbstractController
{
    /**
     * @Route("/{_locale}/supplier/bid/new", name="app_admin_supplier_bid_new", requirements={"_locale": "%app.supported_locales%"})
     */
    public function new(Request $request): Response
    {
        $supplierBid = new SupplierBid();

        if (!$request->isXmlHttpRequest() || !$this->isGranted(AbstractEntityVoter::CREATE, $supplierBid)) {
            throw new NoPermissionException([
                'entity_name' => SupplierBid::class,
                'action'      => AbstractEntityVoter::CREATE,
                'entity_id'   => null
            ]);
        }

        $form = $this->createForm(SupplierBidType::class, $supplierBid);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $supplierBid = $form->getData();
                $this->flush($supplierBid);
                return $this->render('form/successfully_submited.html.twig');
            }
        }

        return $this->render('form/bid_form.html.twig', [
            'form'               => $form->createView(),
            'supplierProposalId' => $request->query->get('proposalId'),
        ]);
    }

    /**
     * @Route("/{_locale}/supplier/bid/{id}/edit", name="app_admin_supplier_bid_edit", requirements={"_locale": "%app.supported_locales%", "id": "\d+"})
     *
     */
    public function edit(SupplierBid $supplierBid, Request $request, ValidatorInterface $validator)
    {
        $property = $request->query->get('property');
        $newValue = $request->query->get('value');
        if (!$request->isXmlHttpRequest() || !$this->isGranted(AbstractEntityVoter::EDIT, $supplierBid) || null === $property || null === $newValue) {
            throw new NoPermissionException([
                'entity_name' => SupplierBid::class,
                'action'      => AbstractEntityVoter::CREATE,
                'entity_id'   => null
            ]);
        }

        $supplierBid->{'set' . $property}($newValue);

        $errors = $validator->validate($supplierBid);

        if (count($errors) > 0) {
            $errorsString = '';
            foreach ($errors as $error) {
                $errorsString = ' ' . $error->getMessage();
            }
            return new JsonResponse($errorsString, 400);
        }

        $this->flush($supplierBid);

        return new JsonResponse((int)$newValue);
    }

    protected function flush(SupplierBidInterface $supplierBid){
        $doctrine    = $this->container->get('doctrine');
        $em          = $doctrine->getManagerForClass(SupplierBid::class);
        $em->persist($supplierBid);
        $em->flush();
    }


}
