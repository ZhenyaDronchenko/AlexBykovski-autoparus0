<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190128194120 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE catalog_turbo_choice_brand (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, headline1 LONGTEXT NOT NULL, headline2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_turbo_choice_city (id INT AUTO_INCREMENT NOT NULL, return_button_link VARCHAR(255) DEFAULT NULL, return_button_text VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, headline1 LONGTEXT NOT NULL, headline2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_turbo_choice_final_page (id INT AUTO_INCREMENT NOT NULL, return_button_link VARCHAR(255) DEFAULT NULL, return_button_text VARCHAR(255) DEFAULT NULL, text3 LONGTEXT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, headline1 LONGTEXT NOT NULL, headline2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_turbo_choice_model (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, headline1 LONGTEXT NOT NULL, headline2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_turbo_choice_spare_part (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, headline1 LONGTEXT NOT NULL, headline2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('DROP TABLE catalog_phone_reason_malfunction_choice_phone_brand');
        $this->addSql('DROP TABLE catalog_phone_reason_malfunction_choice_phone_model');
        $this->addSql('DROP TABLE catalog_phone_reason_malfunction_choice_phone_reason_malfunction');
        $this->addSql('DROP TABLE catalog_phone_reason_malfunction_final_page');

        $this->addSql('INSERT INTO `catalog_turbo_choice_brand` (`title`, `description`, `text1`, `text2`, `headline1`, `headline2`) VALUES ("", "", "", "", "", "")');
        $this->addSql('INSERT INTO `catalog_turbo_choice_model` (`title`, `description`, `text1`, `text2`, `headline1`, `headline2`) VALUES ("", "", "", "", "", "")');
        $this->addSql('INSERT INTO `catalog_turbo_choice_spare_part` (`title`, `description`, `text1`, `text2`, `headline1`, `headline2`) VALUES ("", "", "", "", "", "")');
        $this->addSql('INSERT INTO `catalog_turbo_choice_city` (`title`, `description`, `text1`, `text2`, `headline1`, `headline2`, `return_button_text`, `return_button_link`) VALUES ("", "", "", "", "", "", "", "")');
        $this->addSql('INSERT INTO `catalog_turbo_choice_final_page` (`title`, `description`, `text1`, `text2`, `headline1`, `headline2`, `return_button_text`, `return_button_link`, `text3`) VALUES ("", "", "", "", "", "", "", "", "")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE catalog_phone_reason_malfunction_choice_phone_brand (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE catalog_phone_reason_malfunction_choice_phone_model (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE catalog_phone_reason_malfunction_choice_phone_reason_malfunction (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE catalog_phone_reason_malfunction_final_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('DROP TABLE catalog_turbo_choice_brand');
        $this->addSql('DROP TABLE catalog_turbo_choice_city');
        $this->addSql('DROP TABLE catalog_turbo_choice_final_page');
        $this->addSql('DROP TABLE catalog_turbo_choice_model');
        $this->addSql('DROP TABLE catalog_turbo_choice_spare_part');

        $this->addSql('INSERT INTO `catalog_phone_reason_malfunction_choice_phone_brand` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_reason_malfunction_choice_phone_model` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_reason_malfunction_choice_phone_reason_malfunction` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_reason_malfunction_final_page` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');

    }
}
