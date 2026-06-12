<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220914123329 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE lsi_client_additional_device (id SERIAL NOT NULL, client_license_id INT DEFAULT NULL, license_additional_device_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, valid_from DATE NOT NULL, expired_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A3F1773D65BAD0A ON lsi_client_additional_device (client_license_id)');
        $this->addSql('CREATE INDEX IDX_A3F17734A502650 ON lsi_client_additional_device (license_additional_device_id)');
        $this->addSql('CREATE TABLE lsi_client_addon (id SERIAL NOT NULL, client_license_id INT DEFAULT NULL, license_addon_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_active BOOLEAN NOT NULL, valid_from DATE NOT NULL, expired_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_60B5FC45D65BAD0A ON lsi_client_addon (client_license_id)');
        $this->addSql('CREATE INDEX IDX_60B5FC453034B74F ON lsi_client_addon (license_addon_id)');
        $this->addSql('CREATE TABLE lsi_client_license (id SERIAL NOT NULL, license_id INT DEFAULT NULL, client_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, special_code VARCHAR(6) DEFAULT NULL, valid_from DATE NOT NULL, expired_at DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_41C286B5460F904B ON lsi_client_license (license_id)');
        $this->addSql('CREATE INDEX IDX_41C286B519EB6921 ON lsi_client_license (client_id)');
        $this->addSql('CREATE TABLE lsi_client_set (id SERIAL NOT NULL, client_license_id INT DEFAULT NULL, license_set_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C2AD6FCD65BAD0A ON lsi_client_set (client_license_id)');
        $this->addSql('CREATE INDEX IDX_C2AD6FC6A15631E ON lsi_client_set (license_set_id)');
        $this->addSql('CREATE TABLE lsi_license (id SERIAL NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price_month NUMERIC(10, 2) DEFAULT NULL, price_year NUMERIC(10, 2) DEFAULT NULL, currency VARCHAR(3) DEFAULT NULL, is_visible BOOLEAN NOT NULL, is_active BOOLEAN NOT NULL, is_trial BOOLEAN NOT NULL, included_poses INT NOT NULL, menu_limit INT DEFAULT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE lsi_license_additional_device (id SERIAL NOT NULL, license_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price_month NUMERIC(10, 2) DEFAULT NULL, price_year NUMERIC(10, 2) DEFAULT NULL, currency VARCHAR(3) DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_186D4B8A460F904B ON lsi_license_additional_device (license_id)');
        $this->addSql('CREATE TABLE lsi_license_additional_device_translation (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, locale VARCHAR(5) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A64C8112727ACA70 ON lsi_license_additional_device_translation (parent_id)');
        $this->addSql('CREATE TABLE lsi_license_addon (id SERIAL NOT NULL, license_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price_month NUMERIC(10, 2) DEFAULT NULL, price_year NUMERIC(10, 2) DEFAULT NULL, currency VARCHAR(3) DEFAULT NULL, type VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C6AA9D98460F904B ON lsi_license_addon (license_id)');
        $this->addSql('CREATE TABLE lsi_license_addon_translation (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, locale VARCHAR(5) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E7235FFA727ACA70 ON lsi_license_addon_translation (parent_id)');
        $this->addSql('CREATE TABLE lsi_license_bonus (id SERIAL NOT NULL, license_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_88B7739D460F904B ON lsi_license_bonus (license_id)');
        $this->addSql('CREATE TABLE lsi_license_bonus_translation (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, locale VARCHAR(5) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CEC37286727ACA70 ON lsi_license_bonus_translation (parent_id)');
        $this->addSql('CREATE TABLE lsi_license_set (id SERIAL NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, position INT NOT NULL, price NUMERIC(10, 2) DEFAULT NULL, currency VARCHAR(3) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE lsi_license_set_translation (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, btn_name VARCHAR(255) DEFAULT NULL, btn_url VARCHAR(255) DEFAULT NULL, locale VARCHAR(5) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_92E5423C727ACA70 ON lsi_license_set_translation (parent_id)');
        $this->addSql('CREATE TABLE lsi_license_translation (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, locale VARCHAR(5) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9BE089C9727ACA70 ON lsi_license_translation (parent_id)');
        $this->addSql('ALTER TABLE lsi_client_additional_device ADD CONSTRAINT FK_A3F1773D65BAD0A FOREIGN KEY (client_license_id) REFERENCES lsi_client_license (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_additional_device ADD CONSTRAINT FK_A3F17734A502650 FOREIGN KEY (license_additional_device_id) REFERENCES lsi_license_additional_device (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_addon ADD CONSTRAINT FK_60B5FC45D65BAD0A FOREIGN KEY (client_license_id) REFERENCES lsi_client_license (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_addon ADD CONSTRAINT FK_60B5FC453034B74F FOREIGN KEY (license_addon_id) REFERENCES lsi_license_addon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_license ADD CONSTRAINT FK_41C286B5460F904B FOREIGN KEY (license_id) REFERENCES lsi_license (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_license ADD CONSTRAINT FK_41C286B519EB6921 FOREIGN KEY (client_id) REFERENCES lsi_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_set ADD CONSTRAINT FK_C2AD6FCD65BAD0A FOREIGN KEY (client_license_id) REFERENCES lsi_client_license (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_set ADD CONSTRAINT FK_C2AD6FC6A15631E FOREIGN KEY (license_set_id) REFERENCES lsi_license_set (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_license_additional_device ADD CONSTRAINT FK_186D4B8A460F904B FOREIGN KEY (license_id) REFERENCES lsi_license (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_license_additional_device_translation ADD CONSTRAINT FK_A64C8112727ACA70 FOREIGN KEY (parent_id) REFERENCES lsi_license_additional_device (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_license_addon ADD CONSTRAINT FK_C6AA9D98460F904B FOREIGN KEY (license_id) REFERENCES lsi_license (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_license_addon_translation ADD CONSTRAINT FK_E7235FFA727ACA70 FOREIGN KEY (parent_id) REFERENCES lsi_license_addon (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_license_bonus ADD CONSTRAINT FK_88B7739D460F904B FOREIGN KEY (license_id) REFERENCES lsi_license (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_license_bonus_translation ADD CONSTRAINT FK_CEC37286727ACA70 FOREIGN KEY (parent_id) REFERENCES lsi_license_bonus (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_license_set_translation ADD CONSTRAINT FK_92E5423C727ACA70 FOREIGN KEY (parent_id) REFERENCES lsi_license_set (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_license_translation ADD CONSTRAINT FK_9BE089C9727ACA70 FOREIGN KEY (parent_id) REFERENCES lsi_license (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client DROP expiration_date');
        $this->addSql('ALTER TABLE lsi_client DROP pos_limit');
        $this->addSql('ALTER TABLE lsi_client_payment ADD client_license_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_payment ADD CONSTRAINT FK_7B82F6A1D65BAD0A FOREIGN KEY (client_license_id) REFERENCES lsi_client_license (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7B82F6A1D65BAD0A ON lsi_client_payment (client_license_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lsi_client_additional_device DROP CONSTRAINT FK_A3F1773D65BAD0A');
        $this->addSql('ALTER TABLE lsi_client_addon DROP CONSTRAINT FK_60B5FC45D65BAD0A');
        $this->addSql('ALTER TABLE lsi_client_payment DROP CONSTRAINT FK_7B82F6A1D65BAD0A');
        $this->addSql('ALTER TABLE lsi_client_set DROP CONSTRAINT FK_C2AD6FCD65BAD0A');
        $this->addSql('ALTER TABLE lsi_client_license DROP CONSTRAINT FK_41C286B5460F904B');
        $this->addSql('ALTER TABLE lsi_license_additional_device DROP CONSTRAINT FK_186D4B8A460F904B');
        $this->addSql('ALTER TABLE lsi_license_addon DROP CONSTRAINT FK_C6AA9D98460F904B');
        $this->addSql('ALTER TABLE lsi_license_bonus DROP CONSTRAINT FK_88B7739D460F904B');
        $this->addSql('ALTER TABLE lsi_license_translation DROP CONSTRAINT FK_9BE089C9727ACA70');
        $this->addSql('ALTER TABLE lsi_client_additional_device DROP CONSTRAINT FK_A3F17734A502650');
        $this->addSql('ALTER TABLE lsi_license_additional_device_translation DROP CONSTRAINT FK_A64C8112727ACA70');
        $this->addSql('ALTER TABLE lsi_client_addon DROP CONSTRAINT FK_60B5FC453034B74F');
        $this->addSql('ALTER TABLE lsi_license_addon_translation DROP CONSTRAINT FK_E7235FFA727ACA70');
        $this->addSql('ALTER TABLE lsi_license_bonus_translation DROP CONSTRAINT FK_CEC37286727ACA70');
        $this->addSql('ALTER TABLE lsi_client_set DROP CONSTRAINT FK_C2AD6FC6A15631E');
        $this->addSql('ALTER TABLE lsi_license_set_translation DROP CONSTRAINT FK_92E5423C727ACA70');
        $this->addSql('DROP TABLE lsi_client_additional_device');
        $this->addSql('DROP TABLE lsi_client_addon');
        $this->addSql('DROP TABLE lsi_client_license');
        $this->addSql('DROP TABLE lsi_client_set');
        $this->addSql('DROP TABLE lsi_license');
        $this->addSql('DROP TABLE lsi_license_additional_device');
        $this->addSql('DROP TABLE lsi_license_additional_device_translation');
        $this->addSql('DROP TABLE lsi_license_addon');
        $this->addSql('DROP TABLE lsi_license_addon_translation');
        $this->addSql('DROP TABLE lsi_license_bonus');
        $this->addSql('DROP TABLE lsi_license_bonus_translation');
        $this->addSql('DROP TABLE lsi_license_set');
        $this->addSql('DROP TABLE lsi_license_set_translation');
        $this->addSql('DROP TABLE lsi_license_translation');
        $this->addSql('DROP INDEX IDX_7B82F6A1D65BAD0A');
        $this->addSql('ALTER TABLE lsi_client_payment DROP client_license_id');
        $this->addSql('ALTER TABLE lsi_client ADD expiration_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client ADD pos_limit INT DEFAULT NULL');
    }
}
