<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190905180602 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_theme ADD order_index INT NOT NULL, ADD is_enable TINYINT(1) DEFAULT \'0\' NOT NULL');

        $this->addSql('INSERT INTO `article_theme` (`theme`, `url`, `order_index`, `is_enable`) VALUES ("Новости", "news", 1, 1), ("Самокаты", "scooter", 6, 1), ("Истории", "stories", 8, 1), ("Мероприятия", "activities", 10, 1)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_theme DROP order_index, DROP is_enable');
    }
}
