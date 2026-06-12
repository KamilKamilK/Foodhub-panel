<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Form;

use App\ClientContext\Domain\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'latestClientLicense',
                ClientLicenseEditType::class,
                [
                    'label_attr' => ['style' => 'display: none;'],
                    'required' => true,
                    'locale' => $options['locale'],
                    'mapped' => false,
                    'data' => $options['data']->getLatestClientLicense()
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'router.client.edit.form.submit',
                    'attr' => [
                        'class' => 'col-12 btn-primary',
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'locale' => 'pl',
        ]);
    }
}
