<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181011054506 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE gallery (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_472B783A19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery_photo (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, photo VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_F02A543B4E7AF8F (gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783A19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE gallery_photo ADD CONSTRAINT FK_F02A543B4E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('ALTER TABLE client ADD photo VARCHAR(255) DEFAULT NULL');

        $this->addSql('INSERT INTO gallery (client_id)
                    SELECT id FROM client');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gallery_photo DROP FOREIGN KEY FK_F02A543B4E7AF8F');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE gallery_photo');
        $this->addSql('ALTER TABLE client DROP photo');
    }
}
