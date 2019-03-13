<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190309214721 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE universal_page_brand ADD text2 LONGTEXT NOT NULL, CHANGE text1 text1 LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE universal_page_city ADD text2 LONGTEXT NOT NULL, CHANGE text1 text1 LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE universal_page_spare_part ADD text2 LONGTEXT NOT NULL, CHANGE text1 text1 LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE universal_page_brand DROP text2, CHANGE text1 text1 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE universal_page_city DROP text2, CHANGE text1 text1 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE universal_page_spare_part DROP text2, CHANGE text1 text1 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
