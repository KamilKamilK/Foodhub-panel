<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220927082014 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE lsi_license ADD included_food_hub_order BOOLEAN DEFAULT \'false\' NOT NULL');

        if ($this->isUpdate()) {
            $this->addSql("UPDATE lsi_license SET included_food_hub_order = 'true' WHERE price_month = '155.00'");

            $this->addSql("DELETE FROM lsi_client_addon WHERE license_addon_id IN 
                    (SELECT id FROM lsi_license_addon WHERE type = 'FOODHUBORDER' AND license_id IN 
                                    (SELECT id FROM lsi_license WHERE price_month = '155.00')
                    )
            ");

            $this->addSql("DELETE FROM lsi_license_addon WHERE type = 'FOODHUBORDER' AND license_id IN 
                                    (SELECT id FROM lsi_license WHERE price_month = '155.00')
            ");
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE lsi_license DROP included_food_hub_order');
    }
}
