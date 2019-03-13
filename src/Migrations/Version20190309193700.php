<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190309193700 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE universal_page_brand (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 VARCHAR(255) NOT NULL, last_bread_crumb VARCHAR(255) NOT NULL, return_button_link VARCHAR(255) DEFAULT NULL, return_button_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE universal_page_brand_image (universal_page_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_A46E03EE43E400CB (universal_page_id), UNIQUE INDEX UNIQ_A46E03EE3DA5256D (image_id), PRIMARY KEY(universal_page_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE universal_page_city (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 VARCHAR(255) NOT NULL, last_bread_crumb VARCHAR(255) NOT NULL, return_button_link VARCHAR(255) DEFAULT NULL, return_button_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE universal_page_city_image (universal_page_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_9928CB6943E400CB (universal_page_id), UNIQUE INDEX UNIQ_9928CB693DA5256D (image_id), PRIMARY KEY(universal_page_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE universal_page_spare_part (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 VARCHAR(255) NOT NULL, last_bread_crumb VARCHAR(255) NOT NULL, return_button_link VARCHAR(255) DEFAULT NULL, return_button_text VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE universal_page_spare_part_image (universal_page_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_A1F3CD7E43E400CB (universal_page_id), UNIQUE INDEX UNIQ_A1F3CD7E3DA5256D (image_id), PRIMARY KEY(universal_page_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE universal_page_brand_image ADD CONSTRAINT FK_A46E03EE43E400CB FOREIGN KEY (universal_page_id) REFERENCES universal_page_brand (id)');
        $this->addSql('ALTER TABLE universal_page_brand_image ADD CONSTRAINT FK_A46E03EE3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE universal_page_city_image ADD CONSTRAINT FK_9928CB6943E400CB FOREIGN KEY (universal_page_id) REFERENCES universal_page_city (id)');
        $this->addSql('ALTER TABLE universal_page_city_image ADD CONSTRAINT FK_9928CB693DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE universal_page_spare_part_image ADD CONSTRAINT FK_A1F3CD7E43E400CB FOREIGN KEY (universal_page_id) REFERENCES universal_page_spare_part (id)');
        $this->addSql('ALTER TABLE universal_page_spare_part_image ADD CONSTRAINT FK_A1F3CD7E3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE universal_page_brand_image DROP FOREIGN KEY FK_A46E03EE43E400CB');
        $this->addSql('ALTER TABLE universal_page_city_image DROP FOREIGN KEY FK_9928CB6943E400CB');
        $this->addSql('ALTER TABLE universal_page_spare_part_image DROP FOREIGN KEY FK_A1F3CD7E43E400CB');
        $this->addSql('DROP TABLE universal_page_brand');
        $this->addSql('DROP TABLE universal_page_brand_image');
        $this->addSql('DROP TABLE universal_page_city');
        $this->addSql('DROP TABLE universal_page_city_image');
        $this->addSql('DROP TABLE universal_page_spare_part');
        $this->addSql('DROP TABLE universal_page_spare_part_image');
    }
}
