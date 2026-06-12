<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Form;

use App\ClientContext\Application\DTO\RegistrationRequest;
use App\ClientContext\Domain\Entity\Agreement;
use App\Shared\Domain\Enum\PlacesTypesEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', UserType::class, [
                'required' => true,
                'constraints' => [new Valid()]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => PlacesTypesEnum::getTranslatedFormChoices($this->translator),
                'required' => true,
                'constraints' => [new NotBlank()],
                'attr' => [
                    'style' => 'visibility: hidden; position: absolute'
                ]
            ])
            ->add('agreementIds', ChoiceType::class, [
                    'choices' => self::agreementsParser($options['agreements']),
                    'expanded' => true,
                    'multiple' => true,
                    'choice_label' => false
            ])
            ->add('withProducts', CheckboxType::class, [
                'required' => true,
                'label_attr' => [
                    'style' => 'display: none;'
                ],
                'attr' => [
                    'style' => 'display: none;'
                ]
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options) {
                $agreements = $event->getData()->agreementIds;
                $form = $event->getForm();
                /** @var Agreement $agreement */
                foreach ($options['agreements'] as $key => $agreement) {
                    if ($agreement->getRequired() && !in_array($agreement->getId(), $agreements)) {
                        $form->get('agreementIds')->get($key)->addError(new FormError($this->translator->trans('registration.process.required_agreement')));
                    }
                }
            }
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'POST',
            'required' => true,
            'data_class' => RegistrationRequest::class,
            'allow_extra_fields' => true,
            'agreements' => null
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }

    private static function agreementsParser(array $agreements): array
    {
        $simpleAgreements = [];
        /** @var Agreement $agreement */
        foreach ($agreements as $agreement) {
            $simpleAgreements[$agreement->getTitle()] = $agreement->getId();
        }

        return $simpleAgreements;
    }
}
