<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190705044425 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE import_advert_error (id INT AUTO_INCREMENT NOT NULL, count INT DEFAULT 0 NOT NULL, line_data LONGTEXT NOT NULL, field_value VARCHAR(255) NOT NULL, issue_field VARCHAR(255) NOT NULL, issue VARCHAR(255) NOT NULL, can_add_keyword TINYINT(1) DEFAULT \'0\' NOT NULL, required_values LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brand ADD key_words LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE model ADD key_words LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE spare_part ADD key_words LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE import_advert_error');
        $this->addSql('ALTER TABLE brand DROP key_words');
        $this->addSql('ALTER TABLE model DROP key_words');
        $this->addSql('ALTER TABLE spare_part DROP key_words');
    }
}
