<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190505173038 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE about_general_page ADD city_link VARCHAR(255) DEFAULT NULL, ADD city_title VARCHAR(255) DEFAULT NULL, DROP minsk_url, DROP brest_url, DROP vitebsk_url, DROP gomel_url, DROP grodno_url, DROP mogilev_url');
        $this->addSql('ALTER TABLE news_general_page ADD city_link VARCHAR(255) DEFAULT NULL, ADD city_title VARCHAR(255) DEFAULT NULL, DROP minsk_url, DROP brest_url, DROP vitebsk_url, DROP gomel_url, DROP grodno_url, DROP mogilev_url');
        $this->addSql('ALTER TABLE to_sellers_general_page ADD city_link VARCHAR(255) DEFAULT NULL, ADD city_title VARCHAR(255) DEFAULT NULL, DROP minsk_url, DROP brest_url, DROP vitebsk_url, DROP gomel_url, DROP grodno_url, DROP mogilev_url');
        $this->addSql('ALTER TABLE to_users_general_page ADD city_link VARCHAR(255) DEFAULT NULL, ADD city_title VARCHAR(255) DEFAULT NULL, DROP minsk_url, DROP brest_url, DROP vitebsk_url, DROP gomel_url, DROP grodno_url, DROP mogilev_url');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE about_general_page ADD minsk_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD brest_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD vitebsk_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD gomel_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD grodno_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD mogilev_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP city_link, DROP city_title');
        $this->addSql('ALTER TABLE news_general_page ADD minsk_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD brest_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD vitebsk_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD gomel_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD grodno_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD mogilev_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP city_link, DROP city_title');
        $this->addSql('ALTER TABLE to_sellers_general_page ADD minsk_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD brest_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD vitebsk_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD gomel_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD grodno_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD mogilev_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP city_link, DROP city_title');
        $this->addSql('ALTER TABLE to_users_general_page ADD minsk_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD brest_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD vitebsk_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD gomel_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD grodno_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD mogilev_url VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP city_link, DROP city_title');
    }
}
