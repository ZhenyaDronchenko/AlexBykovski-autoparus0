<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180907144828 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE spare_part (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, name_accusative VARCHAR(255) NOT NULL, name_instrumental VARCHAR(255) NOT NULL, name_genitive VARCHAR(255) NOT NULL, name_plural VARCHAR(255) NOT NULL, alternative_name1 VARCHAR(255) NOT NULL, alternative_name2 VARCHAR(255) NOT NULL, alternative_name3 VARCHAR(255) NOT NULL, alternative_name4 VARCHAR(255) NOT NULL, alternative_name5 VARCHAR(255) NOT NULL, is_popular TINYINT(1) DEFAULT \'1\' NOT NULL, logo VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE spare_part');
    }
}
