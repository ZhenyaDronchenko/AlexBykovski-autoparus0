<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190314063445 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE check_fine_traffic_police (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, return_button_link VARCHAR(255) DEFAULT NULL, return_button_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE check_fine_traffic_police_by_city (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, return_button_link VARCHAR(255) DEFAULT NULL, return_button_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('INSERT INTO `check_fine_traffic_police` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`, `return_button_text`, `return_button_link`) VALUES ("", "", "", "", "", "", "", "")');
        $this->addSql('INSERT INTO `check_fine_traffic_police_by_city` (`title`, `description`, `headline1`, `text1`, `headline2`, `text2`, `return_button_text`, `return_button_link`) VALUES ("", "", "", "", "", "", "", "")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE check_fine_traffic_police');
        $this->addSql('DROP TABLE check_fine_traffic_police_by_city');
    }
}