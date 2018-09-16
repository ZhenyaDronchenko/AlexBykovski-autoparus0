<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180908200645 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE catalog_city_choice_brand (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_city_choice_city (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('INSERT INTO `catalog_city_choice_brand` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`) VALUES ("", "", "", "", "", "")');
        $this->addSql('INSERT INTO `catalog_city_choice_city` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`) VALUES ("", "", "", "", "", "")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE catalog_city_choice_brand');
        $this->addSql('DROP TABLE catalog_city_choice_city');
    }
}