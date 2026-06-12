<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220920100023 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE lsi_client_license_order_addon (id SERIAL NOT NULL, license_order_id INT DEFAULT NULL, license_addon_id INT DEFAULT NULL, price_net NUMERIC(10, 2) DEFAULT NULL, price_gross NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CA1C4F8C71CFFC0F ON lsi_client_license_order_addon (license_order_id)');
        $this->addSql('CREATE INDEX IDX_CA1C4F8C3034B74F ON lsi_client_license_order_addon (license_addon_id)');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ADD CONSTRAINT FK_CA1C4F8C71CFFC0F FOREIGN KEY (license_order_id) REFERENCES lsi_client_license_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ADD CONSTRAINT FK_CA1C4F8C3034B74F FOREIGN KEY (license_addon_id) REFERENCES lsi_license_addon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE lsi_client_license_order_addons');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD price_net NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD price_gross NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD buyer_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD buyer_vat_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD buyer_street VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD buyer_house VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD buyer_flat VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD buyer_city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD buyer_zip VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ADD price_net NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ADD price_gross NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_license ALTER price_month TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license ALTER price_month DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license ALTER price_year TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license ALTER price_year DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_additional_device ALTER price_month TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_additional_device ALTER price_month DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_additional_device ALTER price_year TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_additional_device ALTER price_year DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_addon ALTER price_month TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_addon ALTER price_month DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_addon ALTER price_year TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_addon ALTER price_year DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_set ALTER price TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_set ALTER price DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE lsi_client_license_order_addons (license_order_id INT NOT NULL, license_addon_id INT NOT NULL, PRIMARY KEY(license_order_id, license_addon_id))');
        $this->addSql('CREATE INDEX idx_ffca1c4f71cffc0f ON lsi_client_license_order_addons (license_order_id)');
        $this->addSql('CREATE INDEX idx_ffca1c4f3034b74f ON lsi_client_license_order_addons (license_addon_id)');
        $this->addSql('ALTER TABLE lsi_client_license_order_addons ADD CONSTRAINT fk_ffca1c4f71cffc0f FOREIGN KEY (license_order_id) REFERENCES lsi_client_license_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_license_order_addons ADD CONSTRAINT fk_ffca1c4f3034b74f FOREIGN KEY (license_addon_id) REFERENCES lsi_license_addon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE lsi_client_license_order_addon');
        $this->addSql('ALTER TABLE lsi_license ALTER price_month TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license ALTER price_month DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license ALTER price_year TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license ALTER price_year DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_set ALTER price TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_set ALTER price DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_addon ALTER price_month TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_addon ALTER price_month DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_addon ALTER price_year TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_addon ALTER price_year DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_additional_device ALTER price_month TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_additional_device ALTER price_month DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_additional_device ALTER price_year TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_additional_device ALTER price_year DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_client_license_order_device DROP price_net');
        $this->addSql('ALTER TABLE lsi_client_license_order_device DROP price_gross');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP price_net');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP price_gross');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP buyer_name');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP buyer_vat_number');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP buyer_street');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP buyer_house');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP buyer_flat');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP buyer_city');
        $this->addSql('ALTER TABLE lsi_client_license_order DROP buyer_zip');
    }
}
