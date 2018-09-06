<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180904194825 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('INSERT INTO `vehicle_category` (`category`) VALUES ("Легковой автомобиль"), ("Грузовой"), ("Седельный тягач"), ("Мотоцикл"), ("Квадроцикл / вездеход"), ("Снегоход"), ("Водный транспорт"), ("Спецтехника")');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM `vehicle_category` WHERE `category` IN("Легковой автомобиль", "Грузовой", "Седельный тягач", "Мотоцикл", "Квадроцикл / вездеход", "Снегоход", "Водный транспорт", "Спецтехника")');
    }
}
