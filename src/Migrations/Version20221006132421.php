<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221006132421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Set default 0 for nullable priceNet/priceGross on order line items';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql("UPDATE lsi_client_license_order_addon SET price_net = '0.00' WHERE price_net IS NULL");
        $this->addSql("UPDATE lsi_client_license_order_addon SET price_gross = '0.00' WHERE price_gross IS NULL");
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER COLUMN price_net SET NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER COLUMN price_net SET DEFAULT \'0.00\'');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER COLUMN price_gross SET NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER COLUMN price_gross SET DEFAULT \'0.00\'');

        $this->addSql("UPDATE lsi_client_license_order_device SET price_net = '0.00' WHERE price_net IS NULL");
        $this->addSql("UPDATE lsi_client_license_order_device SET price_gross = '0.00' WHERE price_gross IS NULL");
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER COLUMN price_net SET NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER COLUMN price_net SET DEFAULT \'0.00\'');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER COLUMN price_gross SET NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER COLUMN price_gross SET DEFAULT \'0.00\'');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER COLUMN price_net DROP NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER COLUMN price_gross DROP NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER COLUMN price_net DROP NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER COLUMN price_gross DROP NOT NULL');
    }
}
