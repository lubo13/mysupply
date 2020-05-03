<?php

namespace App\Controller;

use App\Entity\CustomerRequest;
use App\Form\CustomerRequestType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class CustomRequestAdminController extends EasyAdminController
{

    protected function createRequestNewForm(CustomerRequest $customerRequest, $options)
    {
        return $this->get('form.factory')->createBuilder(CustomerRequestType::class, $customerRequest)->getForm();
    }

    protected function createRequestEditForm(CustomerRequest $customerRequest, $options)
    {
        return $this->get('form.factory')->createBuilder(CustomerRequestType::class, $customerRequest)->getForm();
    }

    protected function createRequestListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter)
    {
        $categories = $this->getUser()->getCategories();
        $user       = $this->getUser();
        return $this->get('doctrine')->getManagerForClass(CustomerRequest::class)->getRepository(CustomerRequest::class)->createListQueryBuilder($user,
            $sortDirection, $sortField, $dqlFilter);
    }

    protected function renderRequestTemplate($actionName, $templatePath, array $parameters = [])
    {
        switch ($actionName) {
            case 'list':
                $templatePath         = 'CRUD/request_list.html.twig';
                $parameters['fields'] = $this->config['entities']['Request']['list']['fields'];
                break;
            case 'show':
                $templatePath         = 'CRUD/request_show.html.twig';
                break;
        }

        return $this->render($templatePath, $parameters);
    }

}
