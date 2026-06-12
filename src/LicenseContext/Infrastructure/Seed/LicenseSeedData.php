<?php declare(strict_types=1);

namespace App\LicenseContext\Infrastructure\Seed;

use App\Shared\Domain\Enum\CurrencyEnum;
use App\LicenseContext\Domain\Enum\AdditionalDeviceTypeEnum;
use App\LicenseContext\Domain\Enum\AddonCategoryEnum;
use App\LicenseContext\Domain\Enum\AddonTypeEnum;

class LicenseSeedData
{
    public static function getLicenseData(): array
    {
        return [
            [
                'priceMonth' => '99',
                'priceYear' => '74',
                'currency' => CurrencyEnum::PLN->value,
                'isVisible' => true,
                'isActive' => true,
                'isTrial' => false,
                'includedFoodHubOrder' => false,
                'includedPoses' => 1,
                'menuLimit' => 2,
                'position' => 1,
                'translations' => [
                    'pl' => [
                        'name' => 'Podstawowy',
                        'description' => 'Licencja na stanowisko POS (wybrane urządzenie + drukarka fiskalna)',
                    ],
                    'en' => [
                        'name' => 'Basic',
                        'description' => 'License for a POS (selected device + fiscal printer)',
                    ]
                ],
                'addons' => [
                    [
                        'priceMonth' => '99',
                        'priceYear' => '74',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AddonTypeEnum::UBER->value,
                        'category' => AddonCategoryEnum::DIS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'Uber Eats',
                            ],
                            'en' => [
                                'name' => 'Uber Eats',
                            ]
                        ]
                    ],
                    [
                        'priceMonth' => '99',
                        'priceYear' => '74',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AddonTypeEnum::TAKEAWAY->value,
                        'category' => AddonCategoryEnum::DIS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'Pyszne.pl',
                            ],
                            'en' => [
                                'name' => 'Pyszne.pl',
                            ]
                        ]
                    ],
                    [
                        'priceMonth' => '99',
                        'priceYear' => '74',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AddonTypeEnum::GLOVO->value,
                        'category' => AddonCategoryEnum::DIS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'Glovo',
                            ],
                            'en' => [
                                'name' => 'Glovo',
                            ]
                        ]
                    ],
                    [
                        'priceMonth' => '99',
                        'priceYear' => '74',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AddonTypeEnum::UPMENU->value,
                        'category' => AddonCategoryEnum::DIS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'UpMenu',
                            ],
                            'en' => [
                                'name' => 'UpMenu',
                            ]
                        ]
                    ],
                    [
                        'priceMonth' => '49',
                        'priceYear' => '37',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AddonTypeEnum::FOODHUBORDER->value,
                        'category' => AddonCategoryEnum::FOODHUBORDER->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'QR menu z zamawianiem do stolika i płatnością online',
                            ],
                            'en' => [
                                'name' => 'QR menu with ordering to the table and online payment',
                            ]
                        ]
                    ]
                ],
                'additionalDevices' => [
                    [
                        'priceMonth' => '29',
                        'priceYear' => '29',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AdditionalDeviceTypeEnum::POS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'Dodatkowe stanowisko POS',
                            ],
                            'en' => [
                                'name' => 'Additional POS',
                            ]
                        ]
                    ]
                ],
                'bonuses' => [
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Dostęp i zarządzanie online w chmurze - z dowolnego miejsca',
                            ],
                            'en' => [
                                'name' => 'Online access and management in the cloud - from anywhere',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Równoczesny dostęp dla wielu użytkowników',
                            ],
                            'en' => [
                                'name' => 'Simultaneous access for multiple users',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Bezpłatne aktualizacje systemu',
                            ],
                            'en' => [
                                'name' => 'Free system updates',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Wsparcie techniczne',
                            ],
                            'en' => [
                                'name' => 'Technical support',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Sprzedaż offline',
                            ],
                            'en' => [
                                'name' => 'Offline sales',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'QR menu',
                            ],
                            'en' => [
                                'name' => 'QR menu',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => '2 spersonalizowane menu',
                            ],
                            'en' => [
                                'name' => '2 personalized menus',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Pizza - konfiguracja i dzielenie',
                            ],
                            'en' => [
                                'name' => 'Pizza - configuration and division',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Tworzenie produktów złożonych, zestawów, modyfikatorów i dodatków',
                            ],
                            'en' => [
                                'name' => 'Create composite products, sets, modifiers and add-ons',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Tworzenie promocji',
                            ],
                            'en' => [
                                'name' => 'Creating a promotion',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Obsługa stolików',
                            ],
                            'en' => [
                                'name' => 'Table service',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Dzielenie rachunków',
                            ],
                            'en' => [
                                'name' => 'Bill splitting',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Raportowanie sprzedaży poszczególnych pracowników',
                            ],
                            'en' => [
                                'name' => 'Reporting the sales of individual employees',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Pełne raporty i analizy sprzedażowe z podziałem na grupy',
                            ],
                            'en' => [
                                'name' => 'Full reports and sales analyzes divided into groups',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Generowanie raportów w pdf i xls',
                            ],
                            'en' => [
                                'name' => 'Generating reports in pdf and xls',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Wystawianie faktur z POS',
                            ],
                            'en' => [
                                'name' => 'Invoicing from POS',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Możliwość zawieszenia abonamentu',
                            ],
                            'en' => [
                                'name' => 'Possibility to suspend the subscription',
                            ]
                        ],
                    ],
                ]
            ],
            [
                'priceMonth' => '155',
                'priceYear' => '116',
                'currency' => CurrencyEnum::PLN->value,
                'isVisible' => true,
                'isActive' => true,
                'isTrial' => false,
                'includedFoodHubOrder' => true,
                'includedPoses' => 1,
                'menuLimit' => null,
                'position' => 2,
                'translations' => [
                    'pl' => [
                        'name' => 'Optymalny',
                        'description' => 'Funkcjonalności pakietu Podstawowego plus:',
                    ],
                    'en' => [
                        'name' => 'Optimal',
                        'description' => 'Basic package features plus:',
                    ]
                ],
                'addons' => [
                    [
                        'priceMonth' => '99',
                        'priceYear' => '74',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AddonTypeEnum::UBER->value,
                        'category' => AddonCategoryEnum::DIS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'Uber Eats',
                            ],
                            'en' => [
                                'name' => 'Uber Eats',
                            ]
                        ]
                    ],
                    [
                        'priceMonth' => '99',
                        'priceYear' => '74',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AddonTypeEnum::TAKEAWAY->value,
                        'category' => AddonCategoryEnum::DIS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'Pyszne.pl',
                            ],
                            'en' => [
                                'name' => 'Pyszne.pl',
                            ]
                        ]
                    ],
                    [
                        'priceMonth' => '99',
                        'priceYear' => '74',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AddonTypeEnum::GLOVO->value,
                        'category' => AddonCategoryEnum::DIS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'Glovo',
                            ],
                            'en' => [
                                'name' => 'Glovo',
                            ]
                        ]
                    ],
                    [
                        'priceMonth' => '99',
                        'priceYear' => '74',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AddonTypeEnum::UPMENU->value,
                        'category' => AddonCategoryEnum::DIS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'UpMenu',
                            ],
                            'en' => [
                                'name' => 'UpMenu',
                            ]
                        ]
                    ]
                ],
                'additionalDevices' => [
                    [
                        'priceMonth' => '59',
                        'priceYear' => '59',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AdditionalDeviceTypeEnum::POS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'Dodatkowe stanowisko POS',
                            ],
                            'en' => [
                                'name' => 'Additional POS',
                            ]
                        ]
                    ]
                ],
                'bonuses' => [
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Nielimitowana ilość spersonalizowanych menu',
                            ],
                            'en' => [
                                'name' => 'Unlimited number of personalized menus',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'QR menu z zamawianiem do stolika i płatnością online (tAPP Order)',
                            ],
                            'en' => [
                                'name' => 'QR menu with table ordering and online payment (tAPP Order)',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Wsparcie techniczne',
                            ],
                            'en' => [
                                'name' => 'Technical support',
                            ]
                        ],
                    ],
                ]
            ],
            [
                'priceMonth' => null,
                'priceYear' => null,
                'currency' => null,
                'isVisible' => true,
                'isActive' => true,
                'isTrial' => false,
                'includedFoodHubOrder' => true,
                'includedPoses' => 0,
                'menuLimit' => null,
                'position' => 3,
                'translations' => [
                    'pl' => [
                        'name' => 'Indywidualny',
                        'description' => 'Cena i zakres pakietu są ustalane indywidualnie',
                    ],
                    'en' => [
                        'name' => 'Individual',
                        'description' => 'The price and scope of the package are set individually',
                    ]
                ],
                'addons' => [],
                'additionalDevices' => [],
                'bonuses' => []
            ],
            [
                'priceMonth' => '155',
                'priceYear' => '116',
                'currency' => CurrencyEnum::PLN->value,
                'isVisible' => false,
                'isActive' => true,
                'isTrial' => true,
                'includedFoodHubOrder' => true,
                'includedPoses' => 1,
                'menuLimit' => null,
                'position' => 4,
                'translations' => [
                    'pl' => [
                        'name' => 'Optymalny',
                        'description' => 'Funkcjonalności pakietu Podstawowego plus:',
                    ],
                    'en' => [
                        'name' => 'Optimal',
                        'description' => 'Basic package features plus:',
                    ]
                ],
                'addons' => [
                    [
                        'priceMonth' => '99',
                        'priceYear' => '74',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AddonTypeEnum::UBER->value,
                        'category' => AddonCategoryEnum::DIS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'Uber Eats',
                            ],
                            'en' => [
                                'name' => 'Uber Eats',
                            ]
                        ]
                    ],
                    [
                        'priceMonth' => '99',
                        'priceYear' => '74',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AddonTypeEnum::TAKEAWAY->value,
                        'category' => AddonCategoryEnum::DIS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'Pyszne.pl',
                            ],
                            'en' => [
                                'name' => 'Pyszne.pl',
                            ]
                        ]
                    ],
                    [
                        'priceMonth' => '99',
                        'priceYear' => '74',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AddonTypeEnum::GLOVO->value,
                        'category' => AddonCategoryEnum::DIS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'Glovo',
                            ],
                            'en' => [
                                'name' => 'Glovo',
                            ]
                        ]
                    ],
                    [
                        'priceMonth' => '99',
                        'priceYear' => '74',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AddonTypeEnum::UPMENU->value,
                        'category' => AddonCategoryEnum::DIS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'UpMenu',
                            ],
                            'en' => [
                                'name' => 'UpMenu',
                            ]
                        ]
                    ]
                ],
                'additionalDevices' => [
                    [
                        'priceMonth' => '59',
                        'priceYear' => '59',
                        'currency' => CurrencyEnum::PLN->value,
                        'type' => AdditionalDeviceTypeEnum::POS->value,
                        'translations' => [
                            'pl' => [
                                'name' => 'Dodatkowe stanowisko POS',
                            ],
                            'en' => [
                                'name' => 'Additional POS',
                            ]
                        ]
                    ]
                ],
                'bonuses' => [
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Nielimitowana ilość spersonalizowanych menu',
                            ],
                            'en' => [
                                'name' => 'Unlimited number of personalized menus',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'QR menu z zamawianiem do stolika i płatnością online (tAPP Order)',
                            ],
                            'en' => [
                                'name' => 'QR menu with table ordering and online payment (tAPP Order)',
                            ]
                        ],
                    ],
                    [
                        'translations' => [
                            'pl' => [
                                'name' => 'Wsparcie techniczne',
                            ],
                            'en' => [
                                'name' => 'Technical support',
                            ]
                        ],
                    ],
                ]
            ],
        ];
    }

    public static function getSetData(): array
    {
        return [
            [
                'position' => 1,
                'price' => null,
                'currency' => CurrencyEnum::PLN->value,
                'translations' => [
                    'pl' => [
                        'name' => 'Zestaw na start',
                        'description' => 'Terminal dotykowy Sunmi T2 Mini z wbudowaną drukarką',
                        'btnName' => 'Zobacz sprzęt',
                        'btnUrl' => 'https://foodhub.pl/sprzet'
                    ],
                    'en' => [
                        'name' => 'Starter kit',
                        'description' => 'Sunmi T2 Mini touch terminal with a built-in printer',
                        'btnName' => 'See the hardware',
                        'btnUrl' => 'https://foodhub.pl/sprzet'
                    ]
                ],
            ],
            [
                'position' => 2,
                'price' => null,
                'currency' => CurrencyEnum::PLN->value,
                'translations' => [
                    'pl' => [
                        'name' => 'Zestaw optymalny',
                        'description' => 'Terminal dotykowy Sunmi D2s Lite na stojaku, drukarka fiskalna Posnet Thermal XL2 online',
                        'btnName' => 'Zobacz sprzęt',
                        'btnUrl' => 'https://foodhub.pl/sprzet'
                    ],
                    'en' => [
                        'name' => 'Optimal kit',
                        'description' => 'Sunmi D2s Lite touch terminal on stand, Posnet Thermal XL2 online fiscal printer',
                        'btnName' => 'See the hardware',
                        'btnUrl' => 'https://foodhub.pl/sprzet'
                    ]
                ],
            ],
            [
                'position' => 3,
                'price' => null,
                'currency' => CurrencyEnum::PLN->value,
                'translations' => [
                    'pl' => [
                        'name' => 'Zestaw dla mobilnych',
                        'description' => 'Nowoczesna kasa fiskalna Novitus Next Pro z dotykowym wyświetlaczem',
                        'btnName' => 'Zobacz sprzęt',
                        'btnUrl' => 'https://foodhub.pl/sprzet'
                    ],
                    'en' => [
                        'name' => 'Mobile kit',
                        'description' => 'A modern Novitus Next Pro cash register with a touch screen',
                        'btnName' => 'See the hardware',
                        'btnUrl' => 'https://foodhub.pl/sprzet'
                    ]
                ],
            ],
            [
                'position' => 4,
                'price' => null,
                'currency' => null,
                'translations' => [
                    'pl' => [
                        'name' => 'Zestaw dobrany indywidualnie',
                        'description' => null,
                        'btnName' => null,
                        'btnUrl' => null
                    ],
                    'en' => [
                        'name' => 'Individually set',
                        'description' => null,
                        'btnName' => null,
                        'btnUrl' => null
                    ]
                ],
            ]
        ];
    }
}
