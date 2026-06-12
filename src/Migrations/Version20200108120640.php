<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200108120640 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE lsi_client (id SERIAL NOT NULL, subdomain VARCHAR(255) NOT NULL, db_name VARCHAR(255) NOT NULL, db_password VARCHAR(255) NOT NULL, db_user VARCHAR(255) NOT NULL, created_at DATE DEFAULT NULL, expiration_date DATE DEFAULT NULL, pos_limit INT DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, company_short_name VARCHAR(255) DEFAULT NULL, company_tax_id_number VARCHAR(50) DEFAULT NULL, company_registration_number VARCHAR(50) DEFAULT NULL, address_street VARCHAR(255) DEFAULT NULL, address_zip_code VARCHAR(255) DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, address_country VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE lsi_client_user (id SERIAL NOT NULL, client_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B617C872E7927C74 ON lsi_client_user (email)');
        $this->addSql('CREATE INDEX IDX_B617C87219EB6921 ON lsi_client_user (client_id)');
        $this->addSql('CREATE TABLE lsi_client_payment (id SERIAL NOT NULL, client_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, operation_number VARCHAR(25) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, currency VARCHAR(3) NOT NULL, payment_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, price NUMERIC(12, 2) DEFAULT \'0\' NOT NULL, report TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7B82F6A119EB6921 ON lsi_client_payment (client_id)');
        $this->addSql('COMMENT ON COLUMN lsi_client_payment.report IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE lsi_place_type (id SERIAL NOT NULL, label VARCHAR(50) NOT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE lsi_user (id SERIAL NOT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(512) NOT NULL, salt VARCHAR(512) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE lsi_client_user ADD CONSTRAINT FK_B617C87219EB6921 FOREIGN KEY (client_id) REFERENCES lsi_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_payment ADD CONSTRAINT FK_7B82F6A119EB6921 FOREIGN KEY (client_id) REFERENCES lsi_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lsi_client_user DROP CONSTRAINT FK_B617C87219EB6921');
        $this->addSql('ALTER TABLE lsi_client_payment DROP CONSTRAINT FK_7B82F6A119EB6921');
        $this->addSql('DROP TABLE lsi_client');
        $this->addSql('DROP TABLE lsi_client_user');
        $this->addSql('DROP TABLE lsi_client_payment');
        $this->addSql('DROP TABLE lsi_place_type');
        $this->addSql('DROP TABLE lsi_user');
    }
}
