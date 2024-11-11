<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserType extends AbstractType


{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $event = $options['event'];
        $userRepository = $options['userRepository'];

        $builder
            ->add('name', TextType::class,[
                'attr' => [
                    'placeholder' => 'Name',
                ],
            ])

            ->add('email', EmailType::class,[
                'constraints' => [
                    new Callback(function ($email, ExecutionContextInterface $context) use ($event, $userRepository) {
                        if ($userRepository->checkForDuplicateEmailInEvent($email, $event)) {
                            $context->buildViolation('This email is already registered.')
                                ->addViolation();
                        }
                    }),
                ],
                'attr' => [
                    'placeholder' => 'Enter your Email',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'event' => null,
            'userRepository' => null,
        ]);
    }
}