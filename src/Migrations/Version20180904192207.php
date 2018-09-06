<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180904192207 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE drive_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE engine (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, capacity VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE engine_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gear_box_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, brand_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, model_en VARCHAR(255) NOT NULL, model_ru VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, is_popular TINYINT(1) DEFAULT \'0\' NOT NULL, text LONGTEXT DEFAULT NULL, INDEX IDX_D79572D944F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model_technical_data (id INT AUTO_INCREMENT NOT NULL, vehicle_category_id INT DEFAULT NULL, model_id INT DEFAULT NULL, year_from INT DEFAULT 0 NOT NULL, year_to INT DEFAULT 0 NOT NULL, INDEX IDX_C184781C9C7DE094 (vehicle_category_id), UNIQUE INDEX UNIQ_C184781C7975B7E7 (model_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model_datum_engine_types (model_technical_data_id INT NOT NULL, engine_type_id INT NOT NULL, INDEX IDX_1DAB189B7E4E2B22 (model_technical_data_id), INDEX IDX_1DAB189B577F21F8 (engine_type_id), PRIMARY KEY(model_technical_data_id, engine_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model_datum_drive_types (model_technical_data_id INT NOT NULL, drive_type_id INT NOT NULL, INDEX IDX_AD0A17C57E4E2B22 (model_technical_data_id), INDEX IDX_AD0A17C51B53C22F (drive_type_id), PRIMARY KEY(model_technical_data_id, drive_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model_datum_gear_box_types (model_technical_data_id INT NOT NULL, gear_box_type_id INT NOT NULL, INDEX IDX_E80A4D857E4E2B22 (model_technical_data_id), INDEX IDX_E80A4D856B26046C (gear_box_type_id), PRIMARY KEY(model_technical_data_id, gear_box_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model_datum_engines (model_technical_data_id INT NOT NULL, engine_id INT NOT NULL, INDEX IDX_B53523017E4E2B22 (model_technical_data_id), INDEX IDX_B5352301E78C9C0A (engine_id), PRIMARY KEY(model_technical_data_id, engine_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model_datum_vehicle_types (model_technical_data_id INT NOT NULL, vehicle_type_id INT NOT NULL, INDEX IDX_9BFC18E17E4E2B22 (model_technical_data_id), INDEX IDX_9BFC18E1DA3FD1FC (vehicle_type_id), PRIMARY KEY(model_technical_data_id, vehicle_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle_category (id INT AUTO_INCREMENT NOT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D944F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE model_technical_data ADD CONSTRAINT FK_C184781C9C7DE094 FOREIGN KEY (vehicle_category_id) REFERENCES vehicle_category (id)');
        $this->addSql('ALTER TABLE model_technical_data ADD CONSTRAINT FK_C184781C7975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE model_datum_engine_types ADD CONSTRAINT FK_1DAB189B7E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id)');
        $this->addSql('ALTER TABLE model_datum_engine_types ADD CONSTRAINT FK_1DAB189B577F21F8 FOREIGN KEY (engine_type_id) REFERENCES engine_type (id)');
        $this->addSql('ALTER TABLE model_datum_drive_types ADD CONSTRAINT FK_AD0A17C57E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id)');
        $this->addSql('ALTER TABLE model_datum_drive_types ADD CONSTRAINT FK_AD0A17C51B53C22F FOREIGN KEY (drive_type_id) REFERENCES drive_type (id)');
        $this->addSql('ALTER TABLE model_datum_gear_box_types ADD CONSTRAINT FK_E80A4D857E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id)');
        $this->addSql('ALTER TABLE model_datum_gear_box_types ADD CONSTRAINT FK_E80A4D856B26046C FOREIGN KEY (gear_box_type_id) REFERENCES gear_box_type (id)');
        $this->addSql('ALTER TABLE model_datum_engines ADD CONSTRAINT FK_B53523017E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id)');
        $this->addSql('ALTER TABLE model_datum_engines ADD CONSTRAINT FK_B5352301E78C9C0A FOREIGN KEY (engine_id) REFERENCES engine (id)');
        $this->addSql('ALTER TABLE model_datum_vehicle_types ADD CONSTRAINT FK_9BFC18E17E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id)');
        $this->addSql('ALTER TABLE model_datum_vehicle_types ADD CONSTRAINT FK_9BFC18E1DA3FD1FC FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_type (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE model_datum_drive_types DROP FOREIGN KEY FK_AD0A17C51B53C22F');
        $this->addSql('ALTER TABLE model_datum_engines DROP FOREIGN KEY FK_B5352301E78C9C0A');
        $this->addSql('ALTER TABLE model_datum_engine_types DROP FOREIGN KEY FK_1DAB189B577F21F8');
        $this->addSql('ALTER TABLE model_datum_gear_box_types DROP FOREIGN KEY FK_E80A4D856B26046C');
        $this->addSql('ALTER TABLE model_technical_data DROP FOREIGN KEY FK_C184781C7975B7E7');
        $this->addSql('ALTER TABLE model_datum_engine_types DROP FOREIGN KEY FK_1DAB189B7E4E2B22');
        $this->addSql('ALTER TABLE model_datum_drive_types DROP FOREIGN KEY FK_AD0A17C57E4E2B22');
        $this->addSql('ALTER TABLE model_datum_gear_box_types DROP FOREIGN KEY FK_E80A4D857E4E2B22');
        $this->addSql('ALTER TABLE model_datum_engines DROP FOREIGN KEY FK_B53523017E4E2B22');
        $this->addSql('ALTER TABLE model_datum_vehicle_types DROP FOREIGN KEY FK_9BFC18E17E4E2B22');
        $this->addSql('ALTER TABLE model_technical_data DROP FOREIGN KEY FK_C184781C9C7DE094');
        $this->addSql('ALTER TABLE model_datum_vehicle_types DROP FOREIGN KEY FK_9BFC18E1DA3FD1FC');
        $this->addSql('DROP TABLE drive_type');
        $this->addSql('DROP TABLE engine');
        $this->addSql('DROP TABLE engine_type');
        $this->addSql('DROP TABLE gear_box_type');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE model_technical_data');
        $this->addSql('DROP TABLE model_datum_engine_types');
        $this->addSql('DROP TABLE model_datum_drive_types');
        $this->addSql('DROP TABLE model_datum_gear_box_types');
        $this->addSql('DROP TABLE model_datum_engines');
        $this->addSql('DROP TABLE model_datum_vehicle_types');
        $this->addSql('DROP TABLE vehicle_category');
        $this->addSql('DROP TABLE vehicle_type');
    }
}
