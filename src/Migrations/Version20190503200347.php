<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190503200347 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE obd2forum_choice_brand (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, return_button_link VARCHAR(255) DEFAULT NULL, return_button_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE obd2forum_choice_code (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, return_button_link VARCHAR(255) DEFAULT NULL, return_button_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE obd2forum_choice_model (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, return_button_link VARCHAR(255) DEFAULT NULL, return_button_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE obd2forum_choice_type (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, return_button_link VARCHAR(255) DEFAULT NULL, return_button_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE obd2forum_final_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, return_button_link VARCHAR(255) DEFAULT NULL, return_button_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('INSERT INTO `obd2forum_choice_brand` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`, `return_button_link`, `return_button_text`) VALUES ("", "", "", "", "", "", "", "")');
        $this->addSql('INSERT INTO `obd2forum_choice_code` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`, `return_button_link`, `return_button_text`) VALUES ("", "", "", "", "", "", "", "")');
        $this->addSql('INSERT INTO `obd2forum_choice_model` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`, `return_button_link`, `return_button_text`) VALUES ("", "", "", "", "", "", "", "")');
        $this->addSql('INSERT INTO `obd2forum_choice_type` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`, `return_button_link`, `return_button_text`) VALUES ("", "", "", "", "", "", "", "")');
        $this->addSql('INSERT INTO `obd2forum_final_page` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`, `return_button_link`, `return_button_text`) VALUES ("", "", "", "", "", "", "", "")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE obd2forum_choice_brand');
        $this->addSql('DROP TABLE obd2forum_choice_code');
        $this->addSql('DROP TABLE obd2forum_choice_model');
        $this->addSql('DROP TABLE obd2forum_choice_type');
        $this->addSql('DROP TABLE obd2forum_final_page');
    }
}
