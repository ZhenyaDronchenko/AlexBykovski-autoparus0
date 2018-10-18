<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181018070728 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE catalog_phone_reason_malfunction_choice_phone_brand (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_reason_malfunction_choice_phone_model (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_reason_malfunction_choice_phone_reason_malfunction (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_reason_malfunction_final_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_selling_choice_city (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_selling_choice_phone_brand (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_selling_choice_phone_model (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_selling_final_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_spare_part_choice_city (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_spare_part_choice_phone_brand (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_spare_part_choice_phone_model (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_spare_part_choice_phone_spare_part (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_spare_part_final_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_work_choice_city (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_work_choice_phone_brand (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_work_choice_phone_model (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_work_choice_phone_work (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalog_phone_work_final_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('INSERT INTO `catalog_phone_reason_malfunction_choice_phone_brand` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_reason_malfunction_choice_phone_model` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_reason_malfunction_choice_phone_reason_malfunction` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_reason_malfunction_final_page` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');

        $this->addSql('INSERT INTO `catalog_phone_selling_choice_city` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_selling_choice_phone_brand` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_selling_choice_phone_model` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_selling_final_page` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');

        $this->addSql('INSERT INTO `catalog_phone_spare_part_choice_city` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_spare_part_choice_phone_brand` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_spare_part_choice_phone_model` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_spare_part_choice_phone_spare_part` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_spare_part_final_page` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');

        $this->addSql('INSERT INTO `catalog_phone_work_choice_city` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_work_choice_phone_brand` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_work_choice_phone_model` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_work_choice_phone_work` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
        $this->addSql('INSERT INTO `catalog_phone_work_final_page` (`title`, `description`, `text1`, `text2`) VALUES ("", "", "", "")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE catalog_phone_reason_malfunction_choice_phone_brand');
        $this->addSql('DROP TABLE catalog_phone_reason_malfunction_choice_phone_model');
        $this->addSql('DROP TABLE catalog_phone_reason_malfunction_choice_phone_reason_malfunction');
        $this->addSql('DROP TABLE catalog_phone_reason_malfunction_final_page');
        $this->addSql('DROP TABLE catalog_phone_selling_choice_city');
        $this->addSql('DROP TABLE catalog_phone_selling_choice_phone_brand');
        $this->addSql('DROP TABLE catalog_phone_selling_choice_phone_model');
        $this->addSql('DROP TABLE catalog_phone_selling_final_page');
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
    }
}
