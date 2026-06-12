<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Form;

use App\Shared\Domain\Enum\PlacesTypesEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class PlacesType extends AbstractType
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices'      => PlacesTypesEnum::getTranslatedFormChoices($this->translator),
                'placeholder'  => 'form.register.placeholder.business_type',
                'required'     => true,
                'constraints'  => [new NotBlank()],
                'label_attr'   => ['style' => 'visibility:hidden'],
                'attr'         => ['style' => 'visibility:hidden'],
            ])
            ->add('withProducts', CheckboxType::class, [
                'required'   => true,
                'data'       => false,
                'label_attr' => ['style' => 'display: none;'],
                'attr'       => ['style' => 'display: none;'],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['style' => 'display: none;'],
            ]);
    }
}
