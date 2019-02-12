<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190211192343 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client ADD address VARCHAR(255) DEFAULT NULL, CHANGE is_helper is_helper TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE user_car ADD gear_box_type_id INT DEFAULT NULL, ADD drive_type_id INT DEFAULT NULL, ADD engine_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B87166B26046C FOREIGN KEY (gear_box_type_id) REFERENCES gear_box_type (id)');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B87161B53C22F FOREIGN KEY (drive_type_id) REFERENCES drive_type (id)');
        $this->addSql('CREATE INDEX IDX_9C2B87166B26046C ON user_car (gear_box_type_id)');
        $this->addSql('CREATE INDEX IDX_9C2B87161B53C22F ON user_car (drive_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client DROP address, CHANGE is_helper is_helper TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE user_car DROP FOREIGN KEY FK_9C2B87166B26046C');
        $this->addSql('ALTER TABLE user_car DROP FOREIGN KEY FK_9C2B87161B53C22F');
        $this->addSql('DROP INDEX IDX_9C2B87166B26046C ON user_car');
        $this->addSql('DROP INDEX IDX_9C2B87161B53C22F ON user_car');
        $this->addSql('ALTER TABLE user_car DROP gear_box_type_id, DROP drive_type_id, DROP engine_name');
    }
}
