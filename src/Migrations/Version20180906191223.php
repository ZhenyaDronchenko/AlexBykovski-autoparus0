<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180906191223 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE model_technical_data DROP FOREIGN KEY FK_C184781C9C7DE094');
        $this->addSql('ALTER TABLE model_technical_data ADD CONSTRAINT FK_C184781C9C7DE094 FOREIGN KEY (vehicle_category_id) REFERENCES vehicle_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE model_datum_engine_types DROP FOREIGN KEY FK_1DAB189B577F21F8');
        $this->addSql('ALTER TABLE model_datum_engine_types DROP FOREIGN KEY FK_1DAB189B7E4E2B22');
        $this->addSql('ALTER TABLE model_datum_engine_types ADD CONSTRAINT FK_1DAB189B577F21F8 FOREIGN KEY (engine_type_id) REFERENCES engine_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE model_datum_engine_types ADD CONSTRAINT FK_1DAB189B7E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE model_datum_drive_types DROP FOREIGN KEY FK_AD0A17C51B53C22F');
        $this->addSql('ALTER TABLE model_datum_drive_types DROP FOREIGN KEY FK_AD0A17C57E4E2B22');
        $this->addSql('ALTER TABLE model_datum_drive_types ADD CONSTRAINT FK_AD0A17C51B53C22F FOREIGN KEY (drive_type_id) REFERENCES drive_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE model_datum_drive_types ADD CONSTRAINT FK_AD0A17C57E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE model_datum_gear_box_types DROP FOREIGN KEY FK_E80A4D856B26046C');
        $this->addSql('ALTER TABLE model_datum_gear_box_types DROP FOREIGN KEY FK_E80A4D857E4E2B22');
        $this->addSql('ALTER TABLE model_datum_gear_box_types ADD CONSTRAINT FK_E80A4D856B26046C FOREIGN KEY (gear_box_type_id) REFERENCES gear_box_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE model_datum_gear_box_types ADD CONSTRAINT FK_E80A4D857E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE model_datum_engines DROP FOREIGN KEY FK_B53523017E4E2B22');
        $this->addSql('ALTER TABLE model_datum_engines DROP FOREIGN KEY FK_B5352301E78C9C0A');
        $this->addSql('ALTER TABLE model_datum_engines ADD CONSTRAINT FK_B53523017E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE model_datum_engines ADD CONSTRAINT FK_B5352301E78C9C0A FOREIGN KEY (engine_id) REFERENCES engine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE model_datum_vehicle_types DROP FOREIGN KEY FK_9BFC18E17E4E2B22');
        $this->addSql('ALTER TABLE model_datum_vehicle_types DROP FOREIGN KEY FK_9BFC18E1DA3FD1FC');
        $this->addSql('ALTER TABLE model_datum_vehicle_types ADD CONSTRAINT FK_9BFC18E17E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE model_datum_vehicle_types ADD CONSTRAINT FK_9BFC18E1DA3FD1FC FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE model_datum_drive_types DROP FOREIGN KEY FK_AD0A17C57E4E2B22');
        $this->addSql('ALTER TABLE model_datum_drive_types DROP FOREIGN KEY FK_AD0A17C51B53C22F');
        $this->addSql('ALTER TABLE model_datum_drive_types ADD CONSTRAINT FK_AD0A17C57E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id)');
        $this->addSql('ALTER TABLE model_datum_drive_types ADD CONSTRAINT FK_AD0A17C51B53C22F FOREIGN KEY (drive_type_id) REFERENCES drive_type (id)');
        $this->addSql('ALTER TABLE model_datum_engine_types DROP FOREIGN KEY FK_1DAB189B7E4E2B22');
        $this->addSql('ALTER TABLE model_datum_engine_types DROP FOREIGN KEY FK_1DAB189B577F21F8');
        $this->addSql('ALTER TABLE model_datum_engine_types ADD CONSTRAINT FK_1DAB189B7E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id)');
        $this->addSql('ALTER TABLE model_datum_engine_types ADD CONSTRAINT FK_1DAB189B577F21F8 FOREIGN KEY (engine_type_id) REFERENCES engine_type (id)');
        $this->addSql('ALTER TABLE model_datum_engines DROP FOREIGN KEY FK_B53523017E4E2B22');
        $this->addSql('ALTER TABLE model_datum_engines DROP FOREIGN KEY FK_B5352301E78C9C0A');
        $this->addSql('ALTER TABLE model_datum_engines ADD CONSTRAINT FK_B53523017E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id)');
        $this->addSql('ALTER TABLE model_datum_engines ADD CONSTRAINT FK_B5352301E78C9C0A FOREIGN KEY (engine_id) REFERENCES engine (id)');
        $this->addSql('ALTER TABLE model_datum_gear_box_types DROP FOREIGN KEY FK_E80A4D857E4E2B22');
        $this->addSql('ALTER TABLE model_datum_gear_box_types DROP FOREIGN KEY FK_E80A4D856B26046C');
        $this->addSql('ALTER TABLE model_datum_gear_box_types ADD CONSTRAINT FK_E80A4D857E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id)');
        $this->addSql('ALTER TABLE model_datum_gear_box_types ADD CONSTRAINT FK_E80A4D856B26046C FOREIGN KEY (gear_box_type_id) REFERENCES gear_box_type (id)');
        $this->addSql('ALTER TABLE model_datum_vehicle_types DROP FOREIGN KEY FK_9BFC18E17E4E2B22');
        $this->addSql('ALTER TABLE model_datum_vehicle_types DROP FOREIGN KEY FK_9BFC18E1DA3FD1FC');
        $this->addSql('ALTER TABLE model_datum_vehicle_types ADD CONSTRAINT FK_9BFC18E17E4E2B22 FOREIGN KEY (model_technical_data_id) REFERENCES model_technical_data (id)');
        $this->addSql('ALTER TABLE model_datum_vehicle_types ADD CONSTRAINT FK_9BFC18E1DA3FD1FC FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_type (id)');
        $this->addSql('ALTER TABLE model_technical_data DROP FOREIGN KEY FK_C184781C9C7DE094');
        $this->addSql('ALTER TABLE model_technical_data ADD CONSTRAINT FK_C184781C9C7DE094 FOREIGN KEY (vehicle_category_id) REFERENCES vehicle_category (id)');
    }
}
