<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200505121711 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
       if ($this->isUpdate()) {
           $this->addSql("UPDATE lsi_client_user SET email = 'admin@lsisoftware.pl' WHERE email = 'user@lsisoftware.pl'");
           $this->addSql("
            INSERT INTO lsi_client_user (client_id, email, active) 
            VALUES (
                (SELECT id FROM lsi_client WHERE subdomain = 'localhost'),
                'manager@lsisoftware.pl',
                true
            )");
           $this->addSql("
            INSERT INTO lsi_client_user (client_id, email, active) 
            VALUES (
                (SELECT id FROM lsi_client WHERE subdomain = 'localhost'),
                'kasjer@lsisoftware.pl',
                true
            )");
       }

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
    }
}
