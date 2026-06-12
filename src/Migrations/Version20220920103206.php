<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220920103206 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE lsi_client_license_order ALTER price_net TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_client_license_order ALTER price_net DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_client_license_order ALTER price_gross TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_client_license_order ALTER price_gross DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER price_net TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER price_net DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER price_gross TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER price_gross DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER price_net TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER price_net DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER price_gross TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER price_gross DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_client_payment ADD total_price_net NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_payment ADD total_price_gross NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client_payment DROP price');
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
        $this->addSql('ALTER TABLE lsi_client_payment ADD price NUMERIC(12, 2) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE lsi_client_payment DROP total_price_net');
        $this->addSql('ALTER TABLE lsi_client_payment DROP total_price_gross');
        $this->addSql('ALTER TABLE lsi_license ALTER price_month TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license ALTER price_month DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license ALTER price_year TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license ALTER price_year DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_set ALTER price TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_set ALTER price DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_additional_device ALTER price_month TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_additional_device ALTER price_month DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_additional_device ALTER price_year TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_additional_device ALTER price_year DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_addon ALTER price_month TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_addon ALTER price_month DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_license_addon ALTER price_year TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_license_addon ALTER price_year DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER price_net TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER price_net DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER price_gross TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_client_license_order_addon ALTER price_gross DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_client_license_order ALTER price_net TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_client_license_order ALTER price_net DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_client_license_order ALTER price_gross TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_client_license_order ALTER price_gross DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER price_net TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER price_net DROP DEFAULT');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER price_gross TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE lsi_client_license_order_device ALTER price_gross DROP DEFAULT');
    }
}
