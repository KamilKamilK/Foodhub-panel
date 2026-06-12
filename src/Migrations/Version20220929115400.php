<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220929115400 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE lsi_merchant (id SERIAL NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(50) DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, special_code VARCHAR(6) DEFAULT NULL, is_default BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0DD8881E7927C74 ON lsi_merchant (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0DD8881F54231B3 ON lsi_merchant (special_code)');
        $this->addSql('ALTER TABLE lsi_client ADD firstname VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client ADD lastname VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client ADD phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client ADD email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE lsi_client ADD reg_special_code VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE lsi_merchant');
        $this->addSql('ALTER TABLE lsi_client DROP firstname');
        $this->addSql('ALTER TABLE lsi_client DROP lastname');
        $this->addSql('ALTER TABLE lsi_client DROP phone');
        $this->addSql('ALTER TABLE lsi_client DROP email');
        $this->addSql('ALTER TABLE lsi_client DROP reg_special_code');
    }
}
