<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181005173845 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE main_page ADD text LONGTEXT NOT NULL, ADD title VARCHAR(255) NOT NULL, ADD middle_text VARCHAR(255) NOT NULL, ADD middle_link VARCHAR(255) NOT NULL');

        $this->addSql('UPDATE main_page SET text = description, description = \'\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE main_page SET description = text');

        $this->addSql('ALTER TABLE main_page DROP text, DROP title, DROP middle_text, DROP middle_link');
    }
}
