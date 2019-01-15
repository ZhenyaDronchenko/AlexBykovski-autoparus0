<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190115071634 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_engine (id INT AUTO_INCREMENT NOT NULL, model_id INT DEFAULT NULL, specific_advert_initiator_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, capacity VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_8DFA01B07975B7E7 (model_id), INDEX IDX_8DFA01B0A39C27A5 (specific_advert_initiator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_engine ADD CONSTRAINT FK_8DFA01B07975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE user_engine ADD CONSTRAINT FK_8DFA01B0A39C27A5 FOREIGN KEY (specific_advert_initiator_id) REFERENCES auto_spare_part_specific_advert (id)');
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

        $this->addSql('DROP TABLE user_engine');
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
