<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eventName', TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter event name'
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'Event name must be at least 3 characters long'
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Event name',
                ],
            ])
            ->add('description', TextAreaType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter event description'
                        ]),
                    new Length([
                        'min' => 3,
                        'max' => 500,
                        'minMessage' => 'Event description must be at least 3 characters long',
                        'maxMessage' => 'Event description is to long'
                        ]),
                ],
                'attr' => [
                    'placeholder' => 'Event description',
                ],
            ])

            ->add('registrationLimit', IntegerType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter event registration limit'
                        ]),
                    new Positive([
                        'message' => 'Registration limit must be greater than 0'
                        ]),

                ],
                'attr' => [
                    'min'=>1,
                    'placeholder' => 'Event registration limit',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
