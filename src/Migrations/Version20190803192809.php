<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190803192809 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE phone_model DROP FOREIGN KEY FK_24CF07C344F5D008');
        $this->addSql('DROP TABLE catalog_phone_spare_part_choice_city');
        $this->addSql('DROP TABLE catalog_phone_spare_part_choice_phone_brand');
        $this->addSql('DROP TABLE catalog_phone_spare_part_choice_phone_model');
        $this->addSql('DROP TABLE catalog_phone_spare_part_choice_phone_spare_part');
        $this->addSql('DROP TABLE catalog_phone_spare_part_final_page');
        $this->addSql('DROP TABLE catalog_phone_work_choice_city');
        $this->addSql('DROP TABLE catalog_phone_work_choice_phone_brand');
        $this->addSql('DROP TABLE catalog_phone_work_choice_phone_model');
        $this->addSql('DROP TABLE catalog_phone_work_choice_phone_work');
        $this->addSql('DROP TABLE catalog_phone_work_final_page');
        $this->addSql('DROP TABLE phone_brand');
        $this->addSql('DROP TABLE phone_model');
        $this->addSql('DROP TABLE phone_spare_part');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE catalog_phone_spare_part_choice_city (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text3 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE catalog_phone_spare_part_choice_phone_brand (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE catalog_phone_spare_part_choice_phone_model (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, return_button_link VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, return_button_text VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE catalog_phone_spare_part_choice_phone_spare_part (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE catalog_phone_spare_part_final_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, return_button_link VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, return_button_text VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, text3 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE catalog_phone_work_choice_city (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE catalog_phone_work_choice_phone_brand (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE catalog_phone_work_choice_phone_model (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE catalog_phone_work_choice_phone_work (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE catalog_phone_work_final_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, return_button_link VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, return_button_text VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE phone_brand (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, brand_en VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, brand_ru VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, popular TINYINT(1) DEFAULT \'0\' NOT NULL, logo VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, text LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, active TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE phone_model (id INT AUTO_INCREMENT NOT NULL, brand_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, model_en VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, model_ru VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, logo VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, is_popular TINYINT(1) DEFAULT \'1\' NOT NULL, text1 LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, active TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_24CF07C344F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE phone_spare_part (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, name_accusative VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, name_genitive VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, alternative_name1 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, alternative_name2 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, popular TINYINT(1) DEFAULT \'1\' NOT NULL, logo VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, active TINYINT(1) DEFAULT \'0\' NOT NULL, malfunction1 VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, malfunction2 VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, text3 LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, work VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, action_work VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE phone_model ADD CONSTRAINT FK_24CF07C344F5D008 FOREIGN KEY (brand_id) REFERENCES phone_brand (id)');
    }
}
