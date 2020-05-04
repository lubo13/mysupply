<?php
/**
 * @package Mysupply_App
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */

namespace App\EventSubscriber\Form;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\EventSubscriber\Container;

/**
 * Class RegisterationFormTypeSubscriber
 * @package App\EventSubscriber
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */
class RegisterationFormTypeSubscriber extends Container implements EventSubscriberInterface
{

    /**
     * @return array|\string[][]
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => ['preSetData'],
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $type = $form->get('type')->getData();
        // Yoda syntax
        if (User::SUPPLIER === $type) {
            $entityManager = $this->locator->get('doctrine.orm.entity_manager');
            $categories    = $entityManager->getRepository(Category::class)->findAll();
            $form->add('categories', CollectionType::class, [
                'entry_type'    => ChoiceType::class,
                'label'         => false,
                'compound'      => true,
                'entry_options' => ['choices' => $categories, 'choice_label' => 'name', 'label' => 'form.category'],
                'data'          => ['category' => 'category']
            ]);
        }
    }
}
