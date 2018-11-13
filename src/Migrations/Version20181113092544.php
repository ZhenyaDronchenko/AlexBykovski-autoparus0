<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181113092544 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE about_general_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news_general_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE to_sellers_general_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE to_users_general_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('INSERT INTO `about_general_page` (`title`, `description`, `headline1`, `text1`, `text2`) VALUES ("", "", "", "", "")');
        $this->addSql('INSERT INTO `news_general_page` (`title`, `description`, `headline1`, `text1`, `text2`) VALUES ("", "", "", "", "")');
        $this->addSql('INSERT INTO `to_sellers_general_page` (`title`, `description`, `headline1`, `text1`, `text2`) VALUES ("", "", "", "", "")');
        $this->addSql('INSERT INTO `to_users_general_page` (`title`, `description`, `headline1`, `text1`, `text2`) VALUES ("", "", "", "", "")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE about_general_page');
        $this->addSql('DROP TABLE news_general_page');
        $this->addSql('DROP TABLE to_sellers_general_page');
        $this->addSql('DROP TABLE to_users_general_page');
    }
}
