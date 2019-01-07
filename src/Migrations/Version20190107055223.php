<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190107055223 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE brand CHANGE popular popular TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE model CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE spare_part CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE city CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_brand CHANGE popular popular TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_model CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_spare_part CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE auto_spare_part_general_advert CHANGE is_brand_added is_brand_added TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE client CHANGE is_helper is_helper TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller_company CHANGE is_seller is_seller TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_service is_service TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_news is_news TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller_company_workflow CHANGE is_monday_work is_monday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_tuesday_work is_tuesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_wednesday_work is_wednesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_thursday_work is_thursday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_friday_work is_friday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_saturday_work is_saturday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_sunday_work is_sunday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cash is_cash TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cashless is_cashless TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_credit_card is_credit_card TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE delivery delivery TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE guarantee guarantee TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE about_general_page ADD minsk_url VARCHAR(255) DEFAULT NULL, ADD brest_url VARCHAR(255) DEFAULT NULL, ADD vitebsk_url VARCHAR(255) DEFAULT NULL, ADD gomel_url VARCHAR(255) DEFAULT NULL, ADD grondo_url VARCHAR(255) DEFAULT NULL, ADD mogilev_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE news_general_page ADD minsk_url VARCHAR(255) DEFAULT NULL, ADD brest_url VARCHAR(255) DEFAULT NULL, ADD vitebsk_url VARCHAR(255) DEFAULT NULL, ADD gomel_url VARCHAR(255) DEFAULT NULL, ADD grondo_url VARCHAR(255) DEFAULT NULL, ADD mogilev_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE to_sellers_general_page ADD minsk_url VARCHAR(255) DEFAULT NULL, ADD brest_url VARCHAR(255) DEFAULT NULL, ADD vitebsk_url VARCHAR(255) DEFAULT NULL, ADD gomel_url VARCHAR(255) DEFAULT NULL, ADD grondo_url VARCHAR(255) DEFAULT NULL, ADD mogilev_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE to_users_general_page ADD minsk_url VARCHAR(255) DEFAULT NULL, ADD brest_url VARCHAR(255) DEFAULT NULL, ADD vitebsk_url VARCHAR(255) DEFAULT NULL, ADD gomel_url VARCHAR(255) DEFAULT NULL, ADD grondo_url VARCHAR(255) DEFAULT NULL, ADD mogilev_url VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE about_general_page DROP minsk_url, DROP brest_url, DROP vitebsk_url, DROP gomel_url, DROP grondo_url, DROP mogilev_url');
        $this->addSql('ALTER TABLE auto_spare_part_general_advert CHANGE is_brand_added is_brand_added TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE brand CHANGE popular popular TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE city CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE client CHANGE is_helper is_helper TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE model CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE news_general_page DROP minsk_url, DROP brest_url, DROP vitebsk_url, DROP gomel_url, DROP grondo_url, DROP mogilev_url');
        $this->addSql('ALTER TABLE phone_brand CHANGE popular popular TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_model CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE phone_spare_part CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller_company CHANGE is_seller is_seller TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_service is_service TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_news is_news TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE seller_company_workflow CHANGE is_monday_work is_monday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_tuesday_work is_tuesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_wednesday_work is_wednesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_thursday_work is_thursday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_friday_work is_friday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_saturday_work is_saturday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_sunday_work is_sunday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cash is_cash TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cashless is_cashless TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_credit_card is_credit_card TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE delivery delivery TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE guarantee guarantee TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE spare_part CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE to_sellers_general_page DROP minsk_url, DROP brest_url, DROP vitebsk_url, DROP gomel_url, DROP grondo_url, DROP mogilev_url');
        $this->addSql('ALTER TABLE to_users_general_page DROP minsk_url, DROP brest_url, DROP vitebsk_url, DROP gomel_url, DROP grondo_url, DROP mogilev_url');
    }
}
