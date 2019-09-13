<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190911164635 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE spare_part_request (id INT AUTO_INCREMENT NOT NULL, catalog_request_id INT DEFAULT NULL, spare_part_id INT DEFAULT NULL, spare_part_number VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_FF2882C4F7544A7E (catalog_request_id), INDEX IDX_FF2882C449B7A72 (spare_part_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city_catalog_request (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, phone_by VARCHAR(255) DEFAULT NULL, phone_ru VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_5830F65119EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE spare_part_request ADD CONSTRAINT FK_FF2882C4F7544A7E FOREIGN KEY (catalog_request_id) REFERENCES city_catalog_request (id)');
        $this->addSql('ALTER TABLE spare_part_request ADD CONSTRAINT FK_FF2882C449B7A72 FOREIGN KEY (spare_part_id) REFERENCES spare_part (id)');
        $this->addSql('ALTER TABLE city_catalog_request ADD CONSTRAINT FK_5830F65119EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE spare_part_request DROP FOREIGN KEY FK_FF2882C4F7544A7E');
        $this->addSql('DROP TABLE spare_part_request');
        $this->addSql('DROP TABLE city_catalog_request');
    }
}
