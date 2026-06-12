<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220715114424 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        if ($this->isUpdate()) {
            $this->addSql("INSERT INTO public.lsi_place_type(id, label, name, icon, locale, position) VALUES (17, 'pizzeria', 'Pizzeria', 'flaticon-pizzeria-business', 'pl', 4) ON CONFLICT DO NOTHING;");
            $this->addSql("INSERT INTO public.lsi_place_type(id, label, name, icon, locale, position) VALUES (18, 'pizzeria', 'Pizzeria', 'flaticon-pizzeria-business', 'en', 4) ON CONFLICT DO NOTHING;");
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
    }
}
