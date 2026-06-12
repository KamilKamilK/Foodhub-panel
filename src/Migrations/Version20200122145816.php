<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122145816 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE lsi_client_agreement (agreement_id INT NOT NULL, client_id INT NOT NULL, PRIMARY KEY(agreement_id, client_id))');
        $this->addSql('CREATE INDEX IDX_92DCAF7B24890B2B ON lsi_client_agreement (agreement_id)');
        $this->addSql('CREATE INDEX IDX_92DCAF7B19EB6921 ON lsi_client_agreement (client_id)');
        $this->addSql('ALTER TABLE lsi_client_agreement ADD CONSTRAINT FK_92DCAF7B24890B2B FOREIGN KEY (agreement_id) REFERENCES lsi_agreement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lsi_client_agreement ADD CONSTRAINT FK_92DCAF7B19EB6921 FOREIGN KEY (client_id) REFERENCES lsi_client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE lsi_client_agreement');
    }
}
