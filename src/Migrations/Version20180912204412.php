<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180912204412 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE seller_company (id INT AUTO_INCREMENT NOT NULL, unp VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_car (id INT AUTO_INCREMENT NOT NULL, brand_id INT DEFAULT NULL, model_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_9C2B871644F5D008 (brand_id), INDEX IDX_9C2B87167975B7E7 (model_id), INDEX IDX_9C2B8716A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B871644F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B87167975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B8716A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD forgot_password VARCHAR(255) NOT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD is_helper TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller ADD company_id INT DEFAULT NULL, ADD is_service_station TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_news TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller ADD CONSTRAINT FK_FB1AD3FC979B1AD6 FOREIGN KEY (company_id) REFERENCES seller_company (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FB1AD3FC979B1AD6 ON seller (company_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE seller DROP FOREIGN KEY FK_FB1AD3FC979B1AD6');
        $this->addSql('DROP TABLE seller_company');
        $this->addSql('DROP TABLE user_car');
        $this->addSql('DROP INDEX UNIQ_FB1AD3FC979B1AD6 ON seller');
        $this->addSql('ALTER TABLE seller DROP company_id, DROP is_service_station, DROP is_news');
        $this->addSql('ALTER TABLE user DROP forgot_password, DROP city, DROP is_helper');
    }
}
