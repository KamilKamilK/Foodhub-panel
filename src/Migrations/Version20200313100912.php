<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200313100912 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        if ($this->isUpdate()) {
            $this->addSql('UPDATE lsi_place_type SET icon = \'flaticon-restaurant-business\' WHERE icon = \'flaticon-food-and-restaurant\'');
            $this->addSql('UPDATE lsi_place_type SET icon = \'flaticon-food-track-business\' WHERE icon = \'flaticon-van\'');
            $this->addSql('UPDATE lsi_place_type SET icon = \'flaticon-cafe-business\' WHERE icon = \'flaticon-food-4\'');
            $this->addSql('UPDATE lsi_place_type SET icon = \'flaticon-other-business\' WHERE icon = \'flaticon-dish\'');
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
    }
}
