<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200203151517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE lsi_place_type ADD locale VARCHAR(255) DEFAULT NULL');

        if ($this->isUpdate()) {
            $this->addSql('UPDATE lsi_place_type SET locale = \'en\'');
            $this->addSql('INSERT INTO lsi_place_type (label, name, icon, locale) VALUES (\'restauracja\', \'Restauracja\', \'flaticon-food-and-restaurant\', \'pl\')');
            $this->addSql('INSERT INTO lsi_place_type (label, name, icon, locale) VALUES (\'food-truck\', \'Food Truck\', \'flaticon-van\', \'pl\')');
            $this->addSql('INSERT INTO lsi_place_type (label, name, icon, locale) VALUES (\'kawiarnia\', \'Kawiarnia\', \'flaticon-food-4\', \'pl\')');
            $this->addSql('INSERT INTO lsi_place_type (label, name, icon, locale) VALUES (\'inne\', \'Inne\', \'flaticon-dish\', \'pl\')');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lsi_place_type DROP locale');
    }
}
