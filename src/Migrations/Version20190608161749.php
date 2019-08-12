<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190608161749 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_theme ADD url VARCHAR(255) NOT NULL');

        $urls = [
            [
                "theme" => "Авто",
                "url" => "auto"
            ],
            [
                "theme" => "Мото",
                "url" => "moto"
            ],
            [
                "theme" => "Электро",
                "url" => "electro"
            ],
            [
                "theme" => "Вело",
                "url" => "bicycle"
            ],
            [
                "theme" => "Афиша",
                "url" => "poster"
            ],
            [
                "theme" => "События",
                "url" => "events"
            ],
            [
                "theme" => "Проишествия",
                "url" => "accidents"
            ],
            [
                "theme" => "Сообщества",
                "url" => "сommunities"
            ],
            [
                "theme" => "Путешествия",
                "url" => "travels"
            ],
            [
                "theme" => "Туризм",
                "url" => "tourism"
            ],
            [
                "theme" => "Спорт",
                "url" => "sport"
            ],
            [
                "theme" => "Культура",
                "url" => "culture"
            ],
            [
                "theme" => "Люди",
                "url" => "people"
            ],
            [
                "theme" => "Работа",
                "url" => "job"
            ],
            [
                "theme" => "Технологии",
                "url" => "technologies"
            ],
            [
                "theme" => "Экономика",
                "url" => "economics"
            ],
            [
                "theme" => "Автоспорт",
                "url" => "autosport"
            ],
            [
                "theme" => "Мотоспорт",
                "url" => "motosport"
            ],
            [
                "theme" => "Велоспорт",
                "url" => "cycling"
            ],
            [
                "theme" => "Тест-драйв",
                "url" => "test-drive"
            ],
        ];

        foreach ($urls as $url) {
            $this->addSql('UPDATE article_theme SET url = :url WHERE theme = :theme', $url);
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article_theme DROP url');
    }
}
