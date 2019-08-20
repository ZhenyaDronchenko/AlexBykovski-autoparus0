<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Advert\CurrencyRate;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190820074120 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE currency_rate (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, rate DOUBLE PRECISION NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('INSERT INTO `currency_rate` (`code`, `rate`, `updated_at`) VALUES ("' . CurrencyRate::USD_CODE . '", 0, "2019-08-18 19:25:42"), ("' . CurrencyRate::EUR_CODE . '", 0, "2019-08-18 19:25:42")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE currency_rate');
    }
}
