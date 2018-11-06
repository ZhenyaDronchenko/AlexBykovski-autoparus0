<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181105190713 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE brand ADD url_connect_bamper VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE model ADD url_connect_bamper VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE spare_part ADD url_connect_bamper VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE city ADD url_connect_bamper VARCHAR(255) DEFAULT NULL');

        $this->addSql('UPDATE brand SET url_connect_bamper = url');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE brand DROP url_connect_bamper');
        $this->addSql('ALTER TABLE city DROP url_connect_bamper');
        $this->addSql('ALTER TABLE model DROP url_connect_bamper');
        $this->addSql('ALTER TABLE spare_part DROP url_connect_bamper');
    }
}
