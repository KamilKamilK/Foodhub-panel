<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200117132520 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE lsi_agreement (id SERIAL NOT NULL, title TEXT DEFAULT NULL, description TEXT DEFAULT NULL, required BOOLEAN DEFAULT NULL, locale VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');

        if ($this->isUpdate()) {
            $this->addSql('INSERT INTO lsi_agreement (title, description, required, locale) VALUES (\'Akceptuję <a href="https://gastro.pl/polityka-prywatnosci">politykę prywatności</a>\', null, true, \'pl\')');
            $this->addSql('INSERT INTO lsi_agreement (title, description, required, locale) VALUES (\'Akceptuję <a href="https://gastro.pl/klauzula-informacyjna-art-13-rodo/">politykę RODO</a>\', null, true, \'pl\')');
            $this->addSql('INSERT INTO lsi_agreement (title, description, required, locale) VALUES (\'Akceptuję <a href="https://gastro.pl/polityka-cookies/">politykę Cookies</a>\', null, true, \'pl\')');
            $this->addSql('INSERT INTO lsi_agreement (title, description, required, locale) VALUES (\'Wyrażam zgodę na kierowanie na podany przeze mnie adres e-mail wiadomości marketingowych Współadministratorów LSI. Wiem, że zgoda jest w pełni dobrowolna i w każdej chwili mogę ją odwołać, a jej nieudzielenie nie ma wpływu na realizację na moją rzecz usług przez LSI Software.\', null, false, \'pl\')');
            $this->addSql('INSERT INTO lsi_agreement (title, description, required, locale) VALUES (\'Wyrażam zgodę na kierowanie na podany przeze mnie numer telefonu komunikatów marketingowych Współadministratorów LSI. Wiem, że zgoda jest w pełni dobrowolna i w każdej chwili mogę ją odwołać, a jej nieudzielenie nie ma wpływu na realizację na moją rzecz usług przez LSI Software.\', null, false, \'pl\')');
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE lsi_agreement');
    }
}
