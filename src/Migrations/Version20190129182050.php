<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129182050 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE brand ADD thumbnail_logo32 VARCHAR(255) DEFAULT NULL, ADD thumbnail_logo64 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE model ADD thumbnail_logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE spare_part ADD thumbnail_logo VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE brand DROP thumbnail_logo32, DROP thumbnail_logo64');
        $this->addSql('ALTER TABLE model DROP thumbnail_logo');
        $this->addSql('ALTER TABLE spare_part DROP thumbnail_logo');
    }
}
