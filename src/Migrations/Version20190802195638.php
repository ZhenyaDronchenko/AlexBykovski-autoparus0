<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190802195638 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE auto_spare_part_specific_advert SET spare_part_number = SUBSTRING(spare_part_number, 1, 20)');
        $this->addSql('ALTER TABLE auto_spare_part_specific_advert CHANGE spare_part_number spare_part_number VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE seller_company ADD site_address VARCHAR(255) DEFAULT NULL, ADD link_import_adverts VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auto_spare_part_specific_advert CHANGE spare_part_number spare_part_number LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE seller_company DROP site_address, DROP link_import_adverts');
    }
}
