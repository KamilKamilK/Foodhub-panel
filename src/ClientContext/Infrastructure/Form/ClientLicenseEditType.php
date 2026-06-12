<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Form;

use App\ClientContext\Domain\Entity\ClientLicense;
use App\LicenseContext\Domain\Entity\License;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientLicenseEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'license',
                EntityType::class,
                [
                    'label' => 'router.client.edit.form.license_type',
                    'class' => License::class,
                    'required' => true,
                    'multiple' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('l')
                            ->where('l.isActive = :isActive')
                            ->andWhere('l.priceMonth IS NOT NULL OR l.priceYear IS NOT NULL')
                            ->setParameter('isActive', true)
                            ->orderBy('l.position', 'ASC');
                    },
                    'choice_label' => function (License $license) use ($options) {
                        return $license->getTranslation($options['locale'])->getName() . ($license->isTrial() ? ' (Demo)' : '');
                    }
                ]
            )
            ->add(
                'expiredAt',
                DateType::class,
                [
                    'label' => 'router.client.edit.form.expiration_date',
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => 'yyyy-MM-dd',
                    'required' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientLicense::class,
            'locale' => 'pl',
        ]);
    }
}
