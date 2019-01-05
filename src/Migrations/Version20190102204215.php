<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190102204215 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE auto_spare_part_specific_advert (id INT AUTO_INCREMENT NOT NULL, seller_advert_detail_id INT DEFAULT NULL, brand_id INT DEFAULT NULL, model_id INT DEFAULT NULL, spare_part_id INT DEFAULT NULL, gear_box_type_id INT DEFAULT NULL, vehicle_type_id INT DEFAULT NULL, drive_type_id INT DEFAULT NULL, year INT NOT NULL, engine_type VARCHAR(255) DEFAULT NULL, engine_capacity VARCHAR(255) DEFAULT NULL, engine_name VARCHAR(255) DEFAULT NULL, `condition` VARCHAR(255) NOT NULL, stock_type VARCHAR(255) NOT NULL, spare_part_number VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, cost DOUBLE PRECISION NOT NULL, INDEX IDX_DAE4973A18291EB8 (seller_advert_detail_id), INDEX IDX_DAE4973A44F5D008 (brand_id), INDEX IDX_DAE4973A7975B7E7 (model_id), INDEX IDX_DAE4973A49B7A72 (spare_part_id), INDEX IDX_DAE4973A6B26046C (gear_box_type_id), INDEX IDX_DAE4973ADA3FD1FC (vehicle_type_id), INDEX IDX_DAE4973A1B53C22F (drive_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE auto_spare_part_specific_advert ADD CONSTRAINT FK_DAE4973A18291EB8 FOREIGN KEY (seller_advert_detail_id) REFERENCES seller_advert_detail (id)');
        $this->addSql('ALTER TABLE auto_spare_part_specific_advert ADD CONSTRAINT FK_DAE4973A44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE auto_spare_part_specific_advert ADD CONSTRAINT FK_DAE4973A7975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE auto_spare_part_specific_advert ADD CONSTRAINT FK_DAE4973A49B7A72 FOREIGN KEY (spare_part_id) REFERENCES spare_part (id)');
        $this->addSql('ALTER TABLE auto_spare_part_specific_advert ADD CONSTRAINT FK_DAE4973A6B26046C FOREIGN KEY (gear_box_type_id) REFERENCES gear_box_type (id)');
        $this->addSql('ALTER TABLE auto_spare_part_specific_advert ADD CONSTRAINT FK_DAE4973ADA3FD1FC FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_type (id)');
        $this->addSql('ALTER TABLE auto_spare_part_specific_advert ADD CONSTRAINT FK_DAE4973A1B53C22F FOREIGN KEY (drive_type_id) REFERENCES drive_type (id)');
        $this->addSql('ALTER TABLE auto_spare_part_general_advert CHANGE is_brand_added is_brand_added TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE brand CHANGE popular popular TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE city CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE client CHANGE is_helper is_helper TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller_company CHANGE is_seller is_seller TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_service is_service TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_news is_news TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller_company_workflow CHANGE is_monday_work is_monday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_tuesday_work is_tuesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_wednesday_work is_wednesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_thursday_work is_thursday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_friday_work is_friday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_saturday_work is_saturday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_sunday_work is_sunday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cash is_cash TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cashless is_cashless TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_credit_card is_credit_card TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE delivery delivery TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE guarantee guarantee TINYINT(1) DEFAULT \'0\' NOT NULL');
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

        $this->addSql('DROP TABLE auto_spare_part_specific_advert');
        $this->addSql('ALTER TABLE auto_spare_part_general_advert CHANGE is_brand_added is_brand_added TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE brand CHANGE popular popular TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE city CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE client CHANGE is_helper is_helper TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE model CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_brand CHANGE popular popular TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_model CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_spare_part CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller_company CHANGE is_seller is_seller TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_service is_service TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_news is_news TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller_company_workflow CHANGE is_monday_work is_monday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_tuesday_work is_tuesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_wednesday_work is_wednesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_thursday_work is_thursday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_friday_work is_friday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_saturday_work is_saturday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_sunday_work is_sunday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cash is_cash TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cashless is_cashless TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_credit_card is_credit_card TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE delivery delivery TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE guarantee guarantee TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE spare_part CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
