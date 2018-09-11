<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180911191636 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE catalog_city_choice_body_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, text3 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_city_choice_engine_capacity (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, text3 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_city_choice_engine_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, text3 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_city_choice_final_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, text3 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_city_choice_in_stock (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, text3 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_city_choice_model (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_city_choice_spare_part (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_city_choice_spare_part_status (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, text3 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_city_choice_year (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('INSERT INTO `catalog_city_choice_model` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`) VALUES ("", "", "", "", "", "")');
        $this->addSql('INSERT INTO `catalog_city_choice_year` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`) VALUES ("", "", "", "", "", "")');
        $this->addSql('INSERT INTO `catalog_city_choice_spare_part` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`) VALUES ("", "", "", "", "", "")');

        $this->addSql('INSERT INTO `catalog_city_choice_engine_type` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`, `text3`) VALUES ("", "", "", "", "", "", "")');
        $this->addSql('INSERT INTO `catalog_city_choice_engine_capacity` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`, `text3`) VALUES ("", "", "", "", "", "", "")');
        $this->addSql('INSERT INTO `catalog_city_choice_body_type` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`, `text3`) VALUES ("", "", "", "", "", "", "")');
        $this->addSql('INSERT INTO `catalog_city_choice_spare_part_status` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`, `text3`) VALUES ("", "", "", "", "", "", "")');
        $this->addSql('INSERT INTO `catalog_city_choice_in_stock` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`, `text3`) VALUES ("", "", "", "", "", "", "")');
        $this->addSql('INSERT INTO `catalog_city_choice_final_page` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`, `text3`) VALUES ("", "", "", "", "", "", "")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE catalog_city_choice_body_type');
        $this->addSql('DROP TABLE catalog_city_choice_engine_capacity');
        $this->addSql('DROP TABLE catalog_city_choice_engine_type');
        $this->addSql('DROP TABLE catalog_city_choice_final_page');
        $this->addSql('DROP TABLE catalog_city_choice_in_stock');
        $this->addSql('DROP TABLE catalog_city_choice_model');
        $this->addSql('DROP TABLE catalog_city_choice_spare_part');
        $this->addSql('DROP TABLE catalog_city_choice_spare_part_status');
        $this->addSql('DROP TABLE catalog_city_choice_year');
    }
}
