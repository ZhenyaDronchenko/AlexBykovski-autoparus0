<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190618175926 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, headline VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_5A8A6C8D19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_business_activity (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, city VARCHAR(255) NOT NULL, activity VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, INDEX IDX_920469E54B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_car (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) DEFAULT NULL, engine_type VARCHAR(255) DEFAULT NULL, INDEX IDX_2E7A6BEE4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_photo (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, image_thumbnail_id INT DEFAULT NULL, post_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_83AC08F73DA5256D (image_id), UNIQUE INDEX UNIQ_83AC08F7F73056FE (image_thumbnail_id), INDEX IDX_83AC08F74B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE post_business_activity ADD CONSTRAINT FK_920469E54B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_car ADD CONSTRAINT FK_2E7A6BEE4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_photo ADD CONSTRAINT FK_83AC08F73DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE post_photo ADD CONSTRAINT FK_83AC08F7F73056FE FOREIGN KEY (image_thumbnail_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE post_photo ADD CONSTRAINT FK_83AC08F74B89032C FOREIGN KEY (post_id) REFERENCES post (id)');



        $this->addSql('ALTER TABLE post ADD temp_gallery_photo_id INT DEFAULT NULL');

        $this->addSql('INSERT INTO post (description, `type`, client_id, created_at, temp_gallery_photo_id)
                      (SELECT gph.description as description, gph.`type` as `type`, g.`client_id`, im.`created_at`,
                        gph.id as temp_gallery_photo_id
                      FROM gallery_photo as gph
                      JOIN gallery as g ON g.id = gph.`gallery_id`
                      JOIN image as im ON im.id = gph.`image_id`);');

        $this->addSql('INSERT INTO post_photo (image_id, post_id)
                      (SELECT gph.image_id as image_id, p.id as post_id
                      FROM post as p
                      JOIN gallery_photo as gph ON gph.id = p.temp_gallery_photo_id)');

        $this->addSql('INSERT INTO post_car (brand, model, engine_type, post_id)
                      (SELECT gphc.brand as brand, gphc.model as model, gphc.engine_type as engine_type, p.id as post_id
                      FROM gallery_photo_car as gphc
                      JOIN post as p ON p.temp_gallery_photo_id = gphc.gallery_photo_id)');

        $this->addSql('INSERT INTO post_business_activity (city, activity, company_name, post_id)
                      (SELECT gphba.city as city, gphba.activity as activity, gphba.company_name as company_name, p.id as post_id
                      FROM gallery_photo_business_activity as gphba
                      JOIN post as p ON p.temp_gallery_photo_id = gphba.gallery_photo_id)');

        $this->addSql('ALTER TABLE post DROP COLUMN temp_gallery_photo_id');

        $this->addSql('ALTER TABLE gallery_photo DROP FOREIGN KEY FK_F02A543B4E7AF8F');
        $this->addSql('ALTER TABLE gallery_photo_business_activity DROP FOREIGN KEY FK_749B166A4B139E37');
        $this->addSql('ALTER TABLE gallery_photo_car DROP FOREIGN KEY FK_A41F389F4B139E37');

        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE gallery_photo');
        $this->addSql('DROP TABLE gallery_photo_business_activity');
        $this->addSql('DROP TABLE gallery_photo_car');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        //@@todo need to add custom down as for up


        $this->addSql('ALTER TABLE post_business_activity DROP FOREIGN KEY FK_920469E54B89032C');
        $this->addSql('ALTER TABLE post_car DROP FOREIGN KEY FK_2E7A6BEE4B89032C');
        $this->addSql('ALTER TABLE post_photo DROP FOREIGN KEY FK_83AC08F74B89032C');
        $this->addSql('CREATE TABLE gallery (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_472B783A19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE gallery_photo (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, image_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, type VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_F02A543B4E7AF8F (gallery_id), UNIQUE INDEX UNIQ_F02A543B3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE gallery_photo_business_activity (id INT AUTO_INCREMENT NOT NULL, gallery_photo_id INT DEFAULT NULL, city VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, activity VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, company_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_749B166A4B139E37 (gallery_photo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE gallery_photo_car (id INT AUTO_INCREMENT NOT NULL, gallery_photo_id INT DEFAULT NULL, brand VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, model VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, engine_type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_A41F389F4B139E37 (gallery_photo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783A19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE gallery_photo ADD CONSTRAINT FK_F02A543B3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE gallery_photo ADD CONSTRAINT FK_F02A543B4E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('ALTER TABLE gallery_photo_business_activity ADD CONSTRAINT FK_749B166A4B139E37 FOREIGN KEY (gallery_photo_id) REFERENCES gallery_photo (id)');
        $this->addSql('ALTER TABLE gallery_photo_car ADD CONSTRAINT FK_A41F389F4B139E37 FOREIGN KEY (gallery_photo_id) REFERENCES gallery_photo (id)');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_business_activity');
        $this->addSql('DROP TABLE post_car');
        $this->addSql('DROP TABLE post_photo');
    }
}
