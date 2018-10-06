<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181006100518 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE buyer_data (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_96230BF719EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, is_helper TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seller_company_workflow (id INT AUTO_INCREMENT NOT NULL, is_monday_work TINYINT(1) NOT NULL, is_tuesday_work TINYINT(1) NOT NULL, is_wednesday_work TINYINT(1) NOT NULL, is_thursday_work TINYINT(1) NOT NULL, is_friday_work TINYINT(1) NOT NULL, is_saturday_work TINYINT(1) NOT NULL, is_sunday_work TINYINT(1) NOT NULL, week_days_start_at DATETIME NOT NULL, week_days_end_at DATETIME NOT NULL, weekend_start_at DATETIME NOT NULL, weekend_end_at DATETIME NOT NULL, is_cash TINYINT(1) NOT NULL, is_cashless TINYINT(1) NOT NULL, is_credit_card TINYINT(1) NOT NULL, delivery VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seller_data (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, client_id INT DEFAULT NULL, is_service_station TINYINT(1) DEFAULT \'0\' NOT NULL, is_news TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX UNIQ_AF0A3683979B1AD6 (company_id), UNIQUE INDEX UNIQ_AF0A368319EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE buyer_data ADD CONSTRAINT FK_96230BF719EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seller_data ADD CONSTRAINT FK_AF0A3683979B1AD6 FOREIGN KEY (company_id) REFERENCES seller_company (id)');
        $this->addSql('ALTER TABLE seller_data ADD CONSTRAINT FK_AF0A368319EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');

        $this->addSql('INSERT INTO client (id)
                    SELECT id FROM buyer');

        $this->addSql('INSERT INTO buyer_data (client_id)
                    SELECT id FROM client');

        $this->addSql('UPDATE user 
                    SET role="ROLE_CLIENT", roles=\'a:2:{i:0;s:11:"ROLE_CLIENT";i:1;s:10:"ROLE_BUYER";}\'
                    WHERE role="ROLE_BUYER"');

        $this->addSql('DROP TABLE buyer');
        $this->addSql('DROP TABLE seller');
        $this->addSql('ALTER TABLE user DROP city, DROP is_helper');
        $this->addSql('ALTER TABLE seller_company ADD workflow_id INT DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD is_seller TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_service TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_news TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller_company ADD CONSTRAINT FK_94B1B05E2C7C2CBA FOREIGN KEY (workflow_id) REFERENCES seller_company_workflow (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_94B1B05E2C7C2CBA ON seller_company (workflow_id)');
        $this->addSql('ALTER TABLE user_car DROP FOREIGN KEY FK_9C2B8716A76ED395');
        $this->addSql('DROP INDEX IDX_9C2B8716A76ED395 ON user_car');
        $this->addSql('ALTER TABLE user_car ADD model_id INT DEFAULT NULL, ADD vehicle_id INT DEFAULT NULL, ADD engine_type_id INT DEFAULT NULL, ADD client_id INT DEFAULT NULL, ADD year INT NOT NULL, ADD capacity VARCHAR(255) DEFAULT NULL, DROP brand, DROP model, CHANGE user_id brand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B871644F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B87167975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B8716545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle_type (id)');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B8716577F21F8 FOREIGN KEY (engine_type_id) REFERENCES engine_type (id)');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B871619EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_9C2B871644F5D008 ON user_car (brand_id)');
        $this->addSql('CREATE INDEX IDX_9C2B87167975B7E7 ON user_car (model_id)');
        $this->addSql('CREATE INDEX IDX_9C2B8716545317D1 ON user_car (vehicle_id)');
        $this->addSql('CREATE INDEX IDX_9C2B8716577F21F8 ON user_car (engine_type_id)');
        $this->addSql('CREATE INDEX IDX_9C2B871619EB6921 ON user_car (client_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE buyer_data DROP FOREIGN KEY FK_96230BF719EB6921');
        $this->addSql('ALTER TABLE seller_data DROP FOREIGN KEY FK_AF0A368319EB6921');
        $this->addSql('ALTER TABLE user_car DROP FOREIGN KEY FK_9C2B871619EB6921');
        $this->addSql('ALTER TABLE seller_company DROP FOREIGN KEY FK_94B1B05E2C7C2CBA');
        $this->addSql('CREATE TABLE buyer (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seller (id INT NOT NULL, company_id INT DEFAULT NULL, is_service_station TINYINT(1) DEFAULT \'0\' NOT NULL, is_news TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX UNIQ_FB1AD3FC979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE buyer ADD CONSTRAINT FK_84905FB3BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seller ADD CONSTRAINT FK_FB1AD3FC979B1AD6 FOREIGN KEY (company_id) REFERENCES seller_company (id)');
        $this->addSql('ALTER TABLE seller ADD CONSTRAINT FK_FB1AD3FCBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');

        $this->addSql('INSERT INTO buyer (id)
                    SELECT id FROM client');

        $this->addSql('UPDATE user 
                    SET role="ROLE_BUYER", roles=\'a:1:{i:0;s:10:"ROLE_BUYER";}\'
                    WHERE role="ROLE_CLIENT"');

        $this->addSql('DROP TABLE buyer_data');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE seller_company_workflow');
        $this->addSql('DROP TABLE seller_data');
        $this->addSql('DROP INDEX UNIQ_94B1B05E2C7C2CBA ON seller_company');
        $this->addSql('ALTER TABLE seller_company DROP workflow_id, DROP address, DROP is_seller, DROP is_service, DROP is_news');
        $this->addSql('ALTER TABLE user ADD city VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD is_helper TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE user_car DROP FOREIGN KEY FK_9C2B871644F5D008');
        $this->addSql('ALTER TABLE user_car DROP FOREIGN KEY FK_9C2B87167975B7E7');
        $this->addSql('ALTER TABLE user_car DROP FOREIGN KEY FK_9C2B8716545317D1');
        $this->addSql('ALTER TABLE user_car DROP FOREIGN KEY FK_9C2B8716577F21F8');
        $this->addSql('DROP INDEX IDX_9C2B871644F5D008 ON user_car');
        $this->addSql('DROP INDEX IDX_9C2B87167975B7E7 ON user_car');
        $this->addSql('DROP INDEX IDX_9C2B8716545317D1 ON user_car');
        $this->addSql('DROP INDEX IDX_9C2B8716577F21F8 ON user_car');
        $this->addSql('DROP INDEX IDX_9C2B871619EB6921 ON user_car');
        $this->addSql('ALTER TABLE user_car ADD user_id INT DEFAULT NULL, ADD model VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP brand_id, DROP model_id, DROP vehicle_id, DROP engine_type_id, DROP client_id, DROP year, CHANGE capacity brand VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B8716A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9C2B8716A76ED395 ON user_car (user_id)');
    }
}
