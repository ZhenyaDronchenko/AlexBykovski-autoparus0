<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181010183551 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE city ADD logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE seller_company CHANGE unp unp VARCHAR(255) NOT NULL, CHANGE company_name company_name VARCHAR(255) NOT NULL, CHANGE address address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE seller_company_workflow CHANGE week_days_start_at week_days_start_at DATETIME DEFAULT NULL, CHANGE week_days_end_at week_days_end_at DATETIME DEFAULT NULL, CHANGE weekend_start_at weekend_start_at DATETIME DEFAULT NULL, CHANGE weekend_end_at weekend_end_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE seller_data DROP is_service_station, DROP is_news');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE city DROP logo');
        $this->addSql('ALTER TABLE seller_company CHANGE unp unp VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE company_name company_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE address address VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE seller_company_workflow CHANGE week_days_start_at week_days_start_at DATETIME NOT NULL, CHANGE week_days_end_at week_days_end_at DATETIME NOT NULL, CHANGE weekend_start_at weekend_start_at DATETIME NOT NULL, CHANGE weekend_end_at weekend_end_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE seller_data ADD is_service_station TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_news TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
