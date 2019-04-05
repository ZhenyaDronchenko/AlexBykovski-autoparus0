<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190405110546 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE gallery_photo_car (id INT AUTO_INCREMENT NOT NULL, gallery_photo_id INT DEFAULT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, engine_type VARCHAR(255) NOT NULL, INDEX IDX_A41F389F4B139E37 (gallery_photo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gallery_photo_car ADD CONSTRAINT FK_A41F389F4B139E37 FOREIGN KEY (gallery_photo_id) REFERENCES gallery_photo (id)');
        $this->addSql('ALTER TABLE client ADD thumbnail_photo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404555765217F FOREIGN KEY (thumbnail_photo_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74404555765217F ON client (thumbnail_photo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE gallery_photo_car');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404555765217F');
        $this->addSql('DROP INDEX UNIQ_C74404555765217F ON client');
        $this->addSql('ALTER TABLE client DROP thumbnail_photo_id, CHANGE is_helper is_helper TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
