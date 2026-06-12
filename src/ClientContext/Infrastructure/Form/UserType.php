<?php declare(strict_types=1);


namespace App\ClientContext\Infrastructure\Form;


use App\ClientContext\Application\DTO\UserDTO;
use App\ClientContext\Infrastructure\Form\Validator\SpecialCode\SpecialCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 3, 'max' => 255])],
                'label' => 'form.register.placeholder.user_name',

            ])
            ->add('surname', TextType::class, [
                'required' => true,
                'constraints' => [new NotBlank(), new Length(['min' => 3, 'max' => 255])],
                'label' => 'form.register.placeholder.user_surname',
            ])
            ->add('phone', TelType::class, [
                'required' => true,
                'constraints' => [new NotBlank(), new Regex(['pattern' => '/^\+?[0-9]{3}-?[0-9]{6,12}$/', 'message' => 'registration.validation.phone'])],
                'label' => 'form.register.placeholder.phone',
            ])
            ->add('email', TextType::class, [
                'required' => true,
                'constraints' => [new NotBlank(), new Email()],
                'label' => 'form.register.placeholder.email',
            ])
            ->add('specialCode', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length(['min' => 6, 'max' => 6]),
                    new SpecialCode()
                ],
                'label' => 'form.register.placeholder.specialCode',
            ])
            ->add('password', RepeatedType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min'=>8]),
                    new Regex(['pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\?\=\!\@\#\$\%\^\&\*\(\)\_\+\-\=\,\.\;\:\'\"\`]{8,}$/', 'message' => 'registration.validation.password'])
                ],
                'type' => PasswordType::class,
                'invalid_message' => 'registration.validation.repeat_password',
                'first_options' => [
                    'label' => 'form.register.placeholder.password_first',
                ],
                'second_options' => [
                    'label' => 'form.register.placeholder.password_second',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'POST',
            'required' => true,
            'data_class' => UserDTO::class,
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }

}
