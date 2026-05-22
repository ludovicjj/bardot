<?php

namespace App\Form;

use App\Entity\Review;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReviewType extends AbstractType
{
    public function __construct(private readonly RequestStack $requestStack) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $locale = $this->requestStack->getCurrentRequest()?->getLocale() ?? 'fr';

        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'reviews.form.pseudo_label',
                'constraints' => [
                    new NotBlank(message: 'reviews.pseudo.required'),
                    new Length(min: 2, max: 50, minMessage: 'reviews.pseudo.too_short', maxMessage: 'reviews.pseudo.too_long'),
                ],
            ])
            ->add('rating', ChoiceType::class, [
                'label' => 'reviews.form.rating_label',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
                'constraints' => [
                    new NotBlank(message: 'reviews.rating.required'),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'reviews.form.message_label',
                'attr' => [
                    'rows' => 6,
                ],
                'constraints' => [
                    new NotBlank(message: 'reviews.message.required'),
                    new Length(min: 10, max: 1000, minMessage: 'reviews.message.too_short', maxMessage: 'reviews.message.too_long'),
                ],
            ])
            // Honeypot — hidden from real users; bots fill all inputs and trip the silent-reject in the controller
            ->add('website', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'mapped' => false,
                'constraints' => [new Recaptcha3()],
                'action_name' => 'review',
                'locale' => $locale,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
