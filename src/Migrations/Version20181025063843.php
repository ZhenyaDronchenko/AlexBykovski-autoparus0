<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181025063843 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE catalog_phone_spare_part_choice_city ADD text3 LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE catalog_phone_spare_part_final_page ADD return_button_link VARCHAR(255) DEFAULT NULL, ADD return_button_text VARCHAR(255) DEFAULT NULL, ADD text3 LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE catalog_phone_spare_part_choice_city DROP text3');
        $this->addSql('ALTER TABLE catalog_phone_spare_part_final_page DROP return_button_link, DROP return_button_text, DROP text3');
    }
}
