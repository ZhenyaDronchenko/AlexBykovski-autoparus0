<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190625074930 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE main_page_banner (id INT AUTO_INCREMENT NOT NULL, main_page_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, alt VARCHAR(255) NOT NULL, INDEX IDX_D4C8866FF80DCA0D (main_page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE main_page_banner ADD CONSTRAINT FK_D4C8866FF80DCA0D FOREIGN KEY (main_page_id) REFERENCES main_page (id)');
        $this->addSql('ALTER TABLE main_page ADD logo VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE main_page_banner');
        $this->addSql('ALTER TABLE main_page DROP logo');
    }
}
