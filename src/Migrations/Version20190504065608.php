<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190504065608 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE engine_type ADD url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle_type ADD url VARCHAR(255) DEFAULT NULL');

        $engineTypes = [
            ['type' => 'бензин', 'url' => "petrol"],
            ['type' => 'дизель', 'url' => "diesel"],
            ['type' => 'гибрид', 'url' => "hybrid"],
            ['type' => 'электро', 'url' => "electric"],
        ];

        foreach ($engineTypes as $engineType) {
            $this->addSql('UPDATE engine_type SET url = :url WHERE `type` = :type', $engineType);
        }

        $vehicleTypes = [
            ['type' => 'Автобус', 'url' => "bus"],
            ['type' => 'Внедорожник', 'url' => "suv"],
            ['type' => 'Грузовой до 3,5 тонн', 'url' => "truck-before-3_5"],
            ['type' => 'Грузовой свыше 3,5 тонн', 'url' => "truck-after-3_5"],
            ['type' => 'Кабриолет', 'url' => "сonvertible"],
            ['type' => 'Купе', 'url' => "сoupe"],
            ['type' => 'Минивен', 'url' => "minivan"],
            ['type' => 'Микроавтобус', 'url' => "minibus"],
            ['type' => 'Пикап', 'url' => "pickup"],
            ['type' => 'Седан', 'url' => "sedan"],
            ['type' => 'Универсал', 'url' => "estate"],
            ['type' => 'Фургон', 'url' => "van"],
            ['type' => 'Хэтчбек 3х дв', 'url' => "hatchback-3"],
            ['type' => 'Хэтчбек 5 дв', 'url' => "hatchback-5"],
            ['type' => 'Седельный тягач', 'url' => "truck-tractor"],
            ['type' => 'Мотоцикл', 'url' => "motocycle"],
            ['type' => 'Лодка', 'url' => "boat"],
            ['type' => 'Катер', 'url' => "speedboat"],
            ['type' => 'Гидроцикл', 'url' => "jet-ski"],
            ['type' => 'Прицеп / полуприцеп', 'url' => "trailer-semi-trailer"],
            ['type' => 'Трактор / Сельхозтехника', 'url' => "tractor-agricultural-machinery"],
            ['type' => 'Спецтехника', 'url' => "special-machinery"],
        ];

        foreach ($vehicleTypes as $vehicleType) {
            $this->addSql('UPDATE vehicle_type SET url = :url WHERE `type` = :type', $vehicleType);
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE engine_type DROP url');
        $this->addSql('ALTER TABLE vehicle_type DROP url');
    }
}
