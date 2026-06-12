<?php declare(strict_types=1);

namespace App\ClientContext\DataFixtures;

use App\ClientContext\Domain\Entity\Agreement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadAgreementData extends Fixture
{
    private function getData()
    {
        return [
            [
                'title' => 'Akceptuję <a href="https://gastro.pl/polityka-prywatnosci/">politykę prywatności</a>',
                'description' => null,
                'required' => true,
                'locale' => 'pl',
            ],
            [
                'title' => 'Akceptuję <a href="https://gastro.pl/klauzula-informacyjna-art-13-rodo/">politykę RODO</a>',
                'description' => null,
                'required' => true,
                'locale' => 'pl',
            ],
            [
                'title' => 'Akceptuję <a href="https://gastro.pl/polityka-cookies/">politykę Cookies</a>',
                'description' => null,
                'required' => true,
                'locale' => 'pl',
            ],
            [
                'title' => 'Wyrażam zgodę na kierowanie na podany przeze mnie adres e-mail wiadomości marketingowych Współadministratorów LSI. Wiem, że zgoda jest w pełni dobrowolna i w każdej chwili mogę ją odwołać, a jej nieudzielenie nie ma wpływu na realizację na moją rzecz usług przez LSI Software.',
                'description' => null,
                'required' => false,
                'locale' => 'pl',
            ],
            [
                'title' => 'Wyrażam zgodę na kierowanie na podany przeze mnie numer telefonu komunikatów marketingowych Współadministratorów LSI. Wiem, że zgoda jest w pełni dobrowolna i w każdej chwili mogę ją odwołać, a jej nieudzielenie nie ma wpływu na realizację na moją rzecz usług przez LSI Software.',
                'description' => null,
                'required' => false,
                'locale' => 'pl',
            ],
            [
                'title' => 'I accept <a href="https://gastro.pl/polityka-prywatnosci/">privacy policy</a>',
                'description' => null,
                'required' => true,
                'locale' => 'en',
            ],
            [
                'title' => 'I accept <a href="https://gastro.pl/klauzula-informacyjna-art-13-rodo/">RODO policy</a>',
                'description' => null,
                'required' => true,
                'locale' => 'en',
            ],
            [
                'title' => 'I accept <a href="https://gastro.pl/polityka-cookies/">Cookie policy</a>',
                'description' => null,
                'required' => true,
                'locale' => 'en',
            ],
            [
                'title' => 'I agree for LSI Administrators sending marketing messages to my e-mail address. I know that consent is completely voluntary and I can revoke it at any time, and its absence does not affect the services provided by LSI Software.',
                'description' => null,
                'required' => false,
                'locale' => 'en',
            ],
            [
                'title' => 'I agree for LSI Administrators directing marketing messages to phone number provided by myself. I know that consent is completely voluntary and I can revoke it at any time, and its absence does not affect the services provided by LSI Software.',
                'description' => null,
                'required' => false,
                'locale' => 'en',
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $item) {
            $managementAgreement = Agreement::create(
                title:       $item['title'],
                description: $item['description'],
                required:    $item['required'],
                locale:      $item['locale'],
            );

            $manager->persist($managementAgreement);
            $manager->flush();
        }
    }
}