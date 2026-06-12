<?php declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;

class Version20200117132521 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        if ($this->isUpdate()) {
            $this->addSql('INSERT INTO lsi_agreement (title, description, required, locale) VALUES (\'I accept <a href="https://gastro.pl/polityka-prywatnosci/">privacy policy</a>\', null, true, \'en\')');
            $this->addSql('INSERT INTO lsi_agreement (title, description, required, locale) VALUES (\'I accept <a href="https://gastro.pl/klauzula-informacyjna-art-13-rodo/">RODO policy</a>\', null, true, \'en\')');
            $this->addSql('INSERT INTO lsi_agreement (title, description, required, locale) VALUES (\'I accept <a href="https://gastro.pl/polityka-cookies/">Cookie policy</a>\', null, true, \'en\')');
            $this->addSql('INSERT INTO lsi_agreement (title, description, required, locale) VALUES (\'I agree for LSI Administrators sending marketing messages to my e-mail address. I know that consent is completely voluntary and I can revoke it at any time, and its absence does not affect the services provided by LSI Software.\', null, false, \'en\')');
            $this->addSql('INSERT INTO lsi_agreement (title, description, required, locale) VALUES (\'I agree for LSI Administrators directing marketing messages to phone number provided by myself. I know that consent is completely voluntary and I can revoke it at any time, and its absence does not affect the services provided by LSI Software.\', null, false, \'en\')');
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DELETE FROM lsi_agreement WHERE locale=\'en\'');
    }
}
