<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Client\GalleryPhoto;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190510182127 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE gallery_photo_business_activity (id INT AUTO_INCREMENT NOT NULL, gallery_photo_id INT DEFAULT NULL, city VARCHAR(255) NOT NULL, activity VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, INDEX IDX_749B166A4B139E37 (gallery_photo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gallery_photo_business_activity ADD CONSTRAINT FK_749B166A4B139E37 FOREIGN KEY (gallery_photo_id) REFERENCES gallery_photo (id)');
        $this->addSql('ALTER TABLE gallery_photo ADD type VARCHAR(255) NOT NULL');

        $this->addSql('UPDATE gallery_photo SET `type` = :type', ["type" => GalleryPhoto::SIMPLE_TYPE]);
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE gallery_photo_business_activity');
        $this->addSql('ALTER TABLE gallery_photo DROP type');
    }
}
