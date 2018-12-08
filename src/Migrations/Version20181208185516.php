<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181208185516 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE geo_location (id INT AUTO_INCREMENT NOT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, full_address VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, geo_location_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C53D045FC34F22E (geo_location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FC34F22E FOREIGN KEY (geo_location_id) REFERENCES geo_location (id)');

        //1) start custom queries
        $this->addSql('ALTER TABLE image ADD seller_data_tmp INT DEFAULT NULL, ADD client_tmp INT DEFAULT NULL');

        $this->addSql('
            INSERT INTO image (image, seller_data_tmp)
            SELECT photo, id FROM seller_data WHERE photo IS NOT NULL
         ');

        $this->addSql('
            INSERT INTO image (image, client_tmp)
            SELECT photo, id FROM client WHERE photo IS NOT NULL
         ');
        //1) end custom queries

        $this->addSql('ALTER TABLE client ADD photo_id INT DEFAULT NULL, DROP photo, CHANGE is_helper is_helper TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404557E9E4C8C FOREIGN KEY (photo_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE seller_data ADD photo_id INT DEFAULT NULL, DROP photo');
        $this->addSql('ALTER TABLE seller_data ADD CONSTRAINT FK_AF0A36837E9E4C8C FOREIGN KEY (photo_id) REFERENCES image (id)');

        //2) start custom queries
        $this->addSql('
            UPDATE seller_data
              JOIN image ON seller_data.id = image.seller_data_tmp
            SET seller_data.photo_id = image.id
         ');

        $this->addSql('
            UPDATE client
              JOIN image ON client.id = image.client_tmp
            SET client.photo_id = image.id
         ');

        $this->addSql('ALTER TABLE image DROP seller_data_tmp, DROP client_tmp');
        //2) end custom queries

        $this->addSql('ALTER TABLE auto_spare_part_general_advert CHANGE is_brand_added is_brand_added TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE brand CHANGE popular popular TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE city CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74404557E9E4C8C ON client (photo_id)');
        $this->addSql('ALTER TABLE seller_company CHANGE is_seller is_seller TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_service is_service TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_news is_news TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller_company_workflow CHANGE is_monday_work is_monday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_tuesday_work is_tuesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_wednesday_work is_wednesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_thursday_work is_thursday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_friday_work is_friday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_saturday_work is_saturday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_sunday_work is_sunday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cash is_cash TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cashless is_cashless TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_credit_card is_credit_card TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE delivery delivery TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE guarantee guarantee TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AF0A36837E9E4C8C ON seller_data (photo_id)');
        $this->addSql('ALTER TABLE model CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_brand CHANGE popular popular TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_model CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_spare_part CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE spare_part CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client ADD photo VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE seller_data ADD photo VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');

        //1) start custom queries
        $this->addSql('
            UPDATE seller_data
              JOIN image ON seller_data.photo_id = image.id
            SET seller_data.photo = image.image
         ');

        $this->addSql('
            UPDATE client
              JOIN image ON client.photo_id = image.id
            SET client.photo = image.image
         ');
        //1) end custom queries

        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FC34F22E');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404557E9E4C8C');
        $this->addSql('ALTER TABLE seller_data DROP FOREIGN KEY FK_AF0A36837E9E4C8C');
        $this->addSql('DROP TABLE geo_location');
        $this->addSql('DROP TABLE image');
        $this->addSql('ALTER TABLE auto_spare_part_general_advert CHANGE is_brand_added is_brand_added TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE brand CHANGE popular popular TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE city CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('DROP INDEX UNIQ_C74404557E9E4C8C ON client');
        $this->addSql('ALTER TABLE client DROP photo_id, CHANGE is_helper is_helper TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE model CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_brand CHANGE popular popular TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_model CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_spare_part CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller_company CHANGE is_seller is_seller TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_service is_service TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_news is_news TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller_company_workflow CHANGE is_monday_work is_monday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_tuesday_work is_tuesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_wednesday_work is_wednesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_thursday_work is_thursday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_friday_work is_friday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_saturday_work is_saturday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_sunday_work is_sunday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cash is_cash TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cashless is_cashless TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_credit_card is_credit_card TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE delivery delivery TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE guarantee guarantee TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('DROP INDEX UNIQ_AF0A36837E9E4C8C ON seller_data');
        $this->addSql('ALTER TABLE seller_data DROP photo_id');
        $this->addSql('ALTER TABLE spare_part CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
