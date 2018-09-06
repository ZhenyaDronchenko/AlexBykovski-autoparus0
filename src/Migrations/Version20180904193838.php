<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180904193838 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('INSERT INTO `vehicle_type` (`type`) VALUES ("Автобус"), ("Внедорожник"), ("Грузовой до 3,5 тонн"), ("Грузовой свыше 3,5 тонн"), ("Кабриолет"), ("Купе"), ("Минивен"), ("Микроавтобус"), ("Пикап"), ("Седан"), ("Универсал"), ("Фургон"), ("Хэтчбэк 3х дв"), ("Хэтчбэк 5 дв"), ("Седельный тягач"), ("Мотоцикл"), ("Лодка"), ("Катер"), ("Гидроцикл"), ("Прицеп / полуприцеп"), ("Трактор / Сельхозтехника"), ("Спецтехника")');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM `vehicle_type` WHERE `type` IN("Автобус", "Внедорожник", "Грузовой до 3,5 тонн", "Грузовой свыше 3,5 тонн", "Кабриолет", "Купе", "Минивен", "Микроавтобус", "Пикап", "Седан", "Универсал", "Фургон", "Хэтчбэк 3х дв", "Хэтчбэк 5 дв", "Седельный тягач", "Мотоцикл", "Лодка", "Катер", "Гидроцикл", "Прицеп / полуприцеп", "Трактор / Сельхозтехника", "Спецтехника")');
    }
}
