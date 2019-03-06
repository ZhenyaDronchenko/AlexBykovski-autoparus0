<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190305033555 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gallery_photo ADD image_id INT DEFAULT NULL, DROP photo');
        $this->addSql('ALTER TABLE gallery_photo ADD CONSTRAINT FK_F02A543B3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F02A543B3DA5256D ON gallery_photo (image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gallery_photo DROP FOREIGN KEY FK_F02A543B3DA5256D');
        $this->addSql('DROP INDEX UNIQ_F02A543B3DA5256D ON gallery_photo');
        $this->addSql('ALTER TABLE gallery_photo ADD photo VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP image_id');
    }
}
