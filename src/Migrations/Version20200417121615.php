<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200417121615 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE lsi_client ADD address_building_no VARCHAR(16)');
        $this->addSql('ALTER TABLE lsi_client ADD address_local_no VARCHAR(16) DEFAULT NULL');

        if ($this->isUpdate()) {
            $this->addSql('UPDATE lsi_client SET address_building_no = \'NONE\'');
        }

        $this->addSql('ALTER TABLE lsi_client ALTER address_building_no SET NOT NULL');
        $this->addSql('UPDATE lsi_client SET address_street = SUBSTRING(address_street, 1, 128)');
        $this->addSql('ALTER TABLE lsi_client ALTER address_street TYPE VARCHAR(128)');
        $this->addSql('UPDATE lsi_client SET address_zip_code = SUBSTRING(address_zip_code, 1, 16)');
        $this->addSql('ALTER TABLE lsi_client ALTER address_zip_code TYPE VARCHAR(16)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lsi_client DROP address_building_no');
        $this->addSql('ALTER TABLE lsi_client DROP address_local_no');
        $this->addSql('ALTER TABLE lsi_client ALTER address_street TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE lsi_client ALTER address_zip_code TYPE VARCHAR(255)');
    }
}
