<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181106180707 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE seller_company ADD activity_description LONGTEXT DEFAULT NULL, ADD additional_phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE seller_company_workflow ADD delivery_detail LONGTEXT DEFAULT NULL, ADD guarantee TINYINT(1) DEFAULT \'0\' NOT NULL, ADD guarantee_detail LONGTEXT DEFAULT NULL, CHANGE is_monday_work is_monday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_tuesday_work is_tuesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_wednesday_work is_wednesday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_thursday_work is_thursday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_friday_work is_friday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_saturday_work is_saturday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_sunday_work is_sunday_work TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cash is_cash TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_cashless is_cashless TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_credit_card is_credit_card TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE delivery delivery TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE seller_company DROP activity_description, DROP additional_phone');
        $this->addSql('ALTER TABLE seller_company_workflow DROP delivery_detail, DROP guarantee, DROP guarantee_detail, CHANGE is_monday_work is_monday_work TINYINT(1) NOT NULL, CHANGE is_tuesday_work is_tuesday_work TINYINT(1) NOT NULL, CHANGE is_wednesday_work is_wednesday_work TINYINT(1) NOT NULL, CHANGE is_thursday_work is_thursday_work TINYINT(1) NOT NULL, CHANGE is_friday_work is_friday_work TINYINT(1) NOT NULL, CHANGE is_saturday_work is_saturday_work TINYINT(1) NOT NULL, CHANGE is_sunday_work is_sunday_work TINYINT(1) NOT NULL, CHANGE is_cash is_cash TINYINT(1) NOT NULL, CHANGE is_cashless is_cashless TINYINT(1) NOT NULL, CHANGE is_credit_card is_credit_card TINYINT(1) NOT NULL, CHANGE delivery delivery VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
