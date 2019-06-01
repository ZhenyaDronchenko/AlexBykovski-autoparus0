<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190531073513 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, main_image_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, headline2 VARCHAR(255) NOT NULL, author VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, views INT DEFAULT 0 NOT NULL, direct_views INT DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_23A0E66E4873418 (main_image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_images (article_id INT NOT NULL, article_image_id INT NOT NULL, INDEX IDX_8AD829EA7294869C (article_id), UNIQUE INDEX UNIQ_8AD829EA684DD106 (article_image_id), PRIMARY KEY(article_id, article_image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_banner (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, INDEX IDX_55A2F677294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_detail (id INT AUTO_INCREMENT NOT NULL, brand_id INT DEFAULT NULL, model_id INT DEFAULT NULL, article_id INT DEFAULT NULL, activity_type VARCHAR(255) DEFAULT NULL, INDEX IDX_44C1E81344F5D008 (brand_id), INDEX IDX_44C1E8137975B7E7 (model_id), UNIQUE INDEX UNIQ_44C1E8137294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_details_themes (detail_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_835CAE19D8D003BB (detail_id), INDEX IDX_835CAE1959027487 (theme_id), PRIMARY KEY(detail_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_image (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) DEFAULT NULL, image_thumbnail VARCHAR(255) DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, author VARCHAR(255) DEFAULT NULL, image_text VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_theme (id INT AUTO_INCREMENT NOT NULL, theme VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66E4873418 FOREIGN KEY (main_image_id) REFERENCES article_image (id)');
        $this->addSql('ALTER TABLE article_images ADD CONSTRAINT FK_8AD829EA7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_images ADD CONSTRAINT FK_8AD829EA684DD106 FOREIGN KEY (article_image_id) REFERENCES article_image (id)');
        $this->addSql('ALTER TABLE article_banner ADD CONSTRAINT FK_55A2F677294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_detail ADD CONSTRAINT FK_44C1E81344F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE article_detail ADD CONSTRAINT FK_44C1E8137975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE article_detail ADD CONSTRAINT FK_44C1E8137294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_details_themes ADD CONSTRAINT FK_835CAE19D8D003BB FOREIGN KEY (detail_id) REFERENCES article_detail (id)');
        $this->addSql('ALTER TABLE article_details_themes ADD CONSTRAINT FK_835CAE1959027487 FOREIGN KEY (theme_id) REFERENCES article_theme (id)');

        $this->addSql('INSERT INTO `article_theme` (`theme`) VALUES ("Авто"), ("Мото"), ("Электро"), ("Вело"), 
                              ("Афиша"), ("События"), ("Проишествия"), ("Сообщества"), ("Путешествия"), ("Туризм"), 
                              ("Спорт"), ("Культура"), ("Люди"), ("Работа"), ("Технологии"), ("Экономика"), 
                              ("Автоспорт"), ("Мотоспорт"), ("Велоспорт"), ("Тест-драйв")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_images DROP FOREIGN KEY FK_8AD829EA7294869C');
        $this->addSql('ALTER TABLE article_banner DROP FOREIGN KEY FK_55A2F677294869C');
        $this->addSql('ALTER TABLE article_detail DROP FOREIGN KEY FK_44C1E8137294869C');
        $this->addSql('ALTER TABLE article_details_themes DROP FOREIGN KEY FK_835CAE19D8D003BB');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66E4873418');
        $this->addSql('ALTER TABLE article_images DROP FOREIGN KEY FK_8AD829EA684DD106');
        $this->addSql('ALTER TABLE article_details_themes DROP FOREIGN KEY FK_835CAE1959027487');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_images');
        $this->addSql('DROP TABLE article_banner');
        $this->addSql('DROP TABLE article_detail');
        $this->addSql('DROP TABLE article_details_themes');
        $this->addSql('DROP TABLE article_image');
        $this->addSql('DROP TABLE article_theme');
    }
}
