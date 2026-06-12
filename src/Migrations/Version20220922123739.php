<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220922123739 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE lsi_client_license_order ADD extra_price_net NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD extra_price_gross NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD addons_extra_price_net NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD addons_extra_price_gross NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD devices_extra_price_net NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD devices_extra_price_gross NUMERIC(10, 2) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE lsi_client_license_order DROP extra_price_net');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP extra_price_gross');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP addons_extra_price_net');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP addons_extra_price_gross');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP devices_extra_price_net');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP devices_extra_price_gross');
    }
}
