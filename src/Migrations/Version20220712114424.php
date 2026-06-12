<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220712114424 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        if ($this->isUpdate()) {
            $this->addSql("UPDATE lsi_place_type SET position = 1 WHERE label = 'restaurant'");
            $this->addSql("UPDATE lsi_place_type SET position = 2 WHERE label = 'food-truck'");
            $this->addSql("UPDATE lsi_place_type SET position = 3 WHERE label = 'cafe'");
            $this->addSql("UPDATE lsi_place_type SET position = 4 WHERE label = 'pizzeria'");
            $this->addSql("UPDATE lsi_place_type SET position = 5 WHERE label = 'other'");
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
    }
}
