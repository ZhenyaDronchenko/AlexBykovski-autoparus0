<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181016170242 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE phone_brand (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, brand_en VARCHAR(255) NOT NULL, brand_ru VARCHAR(255) NOT NULL, popular TINYINT(1) DEFAULT \'0\' NOT NULL, logo VARCHAR(255) DEFAULT NULL, text LONGTEXT NOT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone_model (id INT AUTO_INCREMENT NOT NULL, brand_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, model_en VARCHAR(255) NOT NULL, model_ru VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, is_popular TINYINT(1) DEFAULT \'1\' NOT NULL, text1 LONGTEXT DEFAULT NULL, text2 LONGTEXT DEFAULT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_24CF07C344F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone_spare_part (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, name_accusative VARCHAR(255) NOT NULL, name_genitive VARCHAR(255) NOT NULL, alternative_name1 VARCHAR(255) NOT NULL, alternative_name2 VARCHAR(255) NOT NULL, popular TINYINT(1) DEFAULT \'1\' NOT NULL, logo VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, malfunction1 VARCHAR(255) DEFAULT NULL, malfunction2 VARCHAR(255) DEFAULT NULL, text1 LONGTEXT DEFAULT NULL, text2 LONGTEXT DEFAULT NULL, text3 LONGTEXT DEFAULT NULL, work VARCHAR(255) DEFAULT NULL, action_work VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE phone_model ADD CONSTRAINT FK_24CF07C344F5D008 FOREIGN KEY (brand_id) REFERENCES phone_brand (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE phone_model DROP FOREIGN KEY FK_24CF07C344F5D008');
        $this->addSql('DROP TABLE phone_brand');
        $this->addSql('DROP TABLE phone_model');
        $this->addSql('DROP TABLE phone_spare_part');
    }
}
