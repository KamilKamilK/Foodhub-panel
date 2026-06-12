<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220916134248 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE lsi_client_set_id_seq CASCADE');
        $this->addSql('CREATE TABLE lsi_client_license_order (id SERIAL NOT NULL, license_id INT DEFAULT NULL, client_id INT DEFAULT NULL, client_license_id INT DEFAULT NULL, license_set_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, special_code VARCHAR(6) DEFAULT NULL, period VARCHAR(255) NOT NULL, subdomain VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A6AC798A460F904B ON lsi_client_license_order (license_id)');
        $this->addSql('CREATE INDEX IDX_A6AC798A19EB6921 ON lsi_client_license_order (client_id)');
        $this->addSql('CREATE INDEX IDX_A6AC798AD65BAD0A ON lsi_client_license_order (client_license_id)');
        $this->addSql('CREATE INDEX IDX_A6AC798A6A15631E ON lsi_client_license_order (license_set_id)');
        $this->addSql('CREATE TABLE lsi_client_license_order_addons (license_order_id INT NOT NULL, license_addon_id INT NOT NULL, PRIMARY KEY(license_order_id, license_addon_id))');
        $this->addSql('CREATE INDEX IDX_FFCA1C4F71CFFC0F ON lsi_client_license_order_addons (license_order_id)');
        $this->addSql('CREATE INDEX IDX_FFCA1C4F3034B74F ON lsi_client_license_order_addons (license_addon_id)');
        $this->addSql('CREATE TABLE lsi_client_license_order_device (id SERIAL NOT NULL, license_order_id INT DEFAULT NULL, license_device_id INT DEFAULT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D808CF671CFFC0F ON lsi_client_license_order_device (license_order_id)');
        $this->addSql('CREATE INDEX IDX_2D808CF62CE5324A ON lsi_client_license_order_device (license_device_id)');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD CONSTRAINT FK_A6AC798A460F904B FOREIGN KEY (license_id) REFERENCES lsi_license (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD CONSTRAINT FK_A6AC798A19EB6921 FOREIGN KEY (client_id) REFERENCES lsi_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD CONSTRAINT FK_A6AC798AD65BAD0A FOREIGN KEY (client_license_id) REFERENCES lsi_client_license (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_license_order ADD CONSTRAINT FK_A6AC798A6A15631E FOREIGN KEY (license_set_id) REFERENCES lsi_license_set (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_license_order_addons ADD CONSTRAINT FK_FFCA1C4F71CFFC0F FOREIGN KEY (license_order_id) REFERENCES lsi_client_license_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_license_order_addons ADD CONSTRAINT FK_FFCA1C4F3034B74F FOREIGN KEY (license_addon_id) REFERENCES lsi_license_addon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ADD CONSTRAINT FK_2D808CF671CFFC0F FOREIGN KEY (license_order_id) REFERENCES lsi_client_license_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ADD CONSTRAINT FK_2D808CF62CE5324A FOREIGN KEY (license_device_id) REFERENCES lsi_license_additional_device (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE lsi_client_set');
        $this->addSql('ALTER TABLE lsi_client_payment DROP CONSTRAINT fk_7b82f6a119eb6921');
        $this->addSql('ALTER TABLE lsi_client_payment DROP CONSTRAINT fk_7b82f6a1d65bad0a');
        $this->addSql('DROP INDEX idx_7b82f6a119eb6921');
        $this->addSql('DROP INDEX idx_7b82f6a1d65bad0a');
        $this->addSql('ALTER TABLE lsi_client_payment ADD order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_payment ADD payment_type_value VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_payment ADD continue_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_payment ADD payment_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_payment DROP client_id');
        $this->addSql('ALTER TABLE lsi_client_payment DROP client_license_id');
        $this->addSql('ALTER TABLE lsi_client_payment DROP description');
        $this->addSql('ALTER TABLE lsi_client_payment DROP report');
        $this->addSql('ALTER TABLE lsi_client_payment RENAME COLUMN payment_date TO paid_at');
        $this->addSql('ALTER TABLE lsi_client_payment RENAME COLUMN type TO payment_type');
        $this->addSql('ALTER TABLE lsi_client_payment ADD CONSTRAINT FK_7B82F6A18D9F6D38 FOREIGN KEY (order_id) REFERENCES lsi_client_license_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7B82F6A18D9F6D38 ON lsi_client_payment (order_id)');
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
        $this->addSql('ALTER TABLE lsi_client_license_order_addons DROP CONSTRAINT FK_FFCA1C4F71CFFC0F');
        $this->addSql('ALTER TABLE lsi_client_license_order_device DROP CONSTRAINT FK_2D808CF671CFFC0F');
        $this->addSql('ALTER TABLE lsi_client_payment DROP CONSTRAINT FK_7B82F6A18D9F6D38');
        $this->addSql('CREATE SEQUENCE lsi_client_set_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE lsi_client_set (id SERIAL NOT NULL, client_license_id INT DEFAULT NULL, license_set_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_c2ad6fc6a15631e ON lsi_client_set (license_set_id)');
        $this->addSql('CREATE INDEX idx_c2ad6fcd65bad0a ON lsi_client_set (client_license_id)');
        $this->addSql('ALTER TABLE lsi_client_set ADD CONSTRAINT fk_c2ad6fcd65bad0a FOREIGN KEY (client_license_id) REFERENCES lsi_client_license (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_set ADD CONSTRAINT fk_c2ad6fc6a15631e FOREIGN KEY (license_set_id) REFERENCES lsi_license_set (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE lsi_client_license_order');
        $this->addSql('DROP TABLE lsi_client_license_order_addons');
        $this->addSql('DROP TABLE lsi_client_license_order_device');
        $this->addSql('DROP INDEX UNIQ_7B82F6A18D9F6D38');
        $this->addSql('ALTER TABLE lsi_client_payment ADD client_license_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_payment ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_payment ADD description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_payment ADD report TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_payment DROP payment_type');
        $this->addSql('ALTER TABLE lsi_client_payment DROP payment_type_value');
        $this->addSql('ALTER TABLE lsi_client_payment DROP continue_url');
        $this->addSql('ALTER TABLE lsi_client_payment DROP payment_url');
        $this->addSql('ALTER TABLE lsi_client_payment RENAME COLUMN order_id TO client_id');
        $this->addSql('ALTER TABLE lsi_client_payment RENAME COLUMN paid_at TO payment_date');
        $this->addSql('COMMENT ON COLUMN lsi_client_payment.report IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE lsi_client_payment ADD CONSTRAINT fk_7b82f6a119eb6921 FOREIGN KEY (client_id) REFERENCES lsi_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_payment ADD CONSTRAINT fk_7b82f6a1d65bad0a FOREIGN KEY (client_license_id) REFERENCES lsi_client_license (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_7b82f6a119eb6921 ON lsi_client_payment (client_id)');
        $this->addSql('CREATE INDEX idx_7b82f6a1d65bad0a ON lsi_client_payment (client_license_id)');
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
        $this->addSql('ALTER TABLE lsi_license ALTER price_month TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license ALTER price_month DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license ALTER price_year TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license ALTER price_year DROP DEFAULT');
    }
}
