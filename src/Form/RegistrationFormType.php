<?php

namespace App\Form;

use App\Entity\User;
use App\EventSubscriber\Form\RegisterationFormTypeSubscriber;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\RuntimeException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class RegistrationFormType extends AbstractType implements ServiceSubscriberInterface
{
    private $locator;

    public function __construct(ContainerInterface $locator)
    {
        $this->locator = $locator;
    }

    public static function getSubscribedServices()
    {
        return [
            RegisterationFormTypeSubscriber::class => RegisterationFormTypeSubscriber::class,
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!in_array($options['type'], User::AVAILABLE_TYPES)) {
            throw new RuntimeException('The type doesn\'t exist');
        }

        $builder
            ->add('email', null, ['label' => 'form.email'])
            ->add('type', HiddenType::class, ['data' => $options['type'], 'mapped' => false]);

        $builder->addEventSubscriber($this->locator->get(RegisterationFormTypeSubscriber::class));

        $builder->add('agreeTerms', CheckboxType::class, [
            'mapped'      => false,
            'label'       => 'form.agree_term',
            'constraints' => [
                new IsTrue([
                    'message' => 'You should agree to our terms.',
                ]),
            ],
        ])
            ->add('plainPassword', PasswordType::class, [
                'mapped'      => false,
                'label'       => 'form.plain_password',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min'        => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max'        => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'type'       => User::CUSTOMER,
        ]);
    }
}
