<?php

namespace App\Form;

use App\Entity\EvaluationCriteria;
use App\Entity\EvaluationScore;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationScoreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $form->add('evaluationCriteria', EntityType::class,
                [
                    'required' => true,
                    'class'    => EvaluationCriteria::class,
                    'choices'  => [$data->getEvaluationCriteria()]
                ]
            )->add('description');
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EvaluationScore::class,
        ]);
    }
}
