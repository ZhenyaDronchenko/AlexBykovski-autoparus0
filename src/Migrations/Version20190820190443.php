<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190820190443 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auto_spare_part_specific_advert ADD spare_part_id INT DEFAULT NULL');

        $this->addSql('
            UPDATE auto_spare_part_specific_advert AS advert
              INNER JOIN spare_part AS sp ON advert.spare_part = sp.name
              SET advert.spare_part_id = sp.id 
         ');

        $this->addSql('DELETE advert FROM auto_spare_part_specific_advert AS advert WHERE spare_part_id IS NULL');

        $this->addSql('ALTER TABLE auto_spare_part_specific_advert DROP spare_part');

        $this->addSql('ALTER TABLE auto_spare_part_specific_advert ADD CONSTRAINT FK_DAE4973A49B7A72 FOREIGN KEY (spare_part_id) REFERENCES spare_part (id)');
        $this->addSql('CREATE INDEX IDX_DAE4973A49B7A72 ON auto_spare_part_specific_advert (spare_part_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auto_spare_part_specific_advert ADD spare_part VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');

        $this->addSql('
            UPDATE auto_spare_part_specific_advert AS advert
              INNER JOIN spare_part AS sp ON advert.spare_part_id = sp.id
              SET advert.spare_part = sp.name 
         ');

        $this->addSql('ALTER TABLE auto_spare_part_specific_advert DROP FOREIGN KEY FK_DAE4973A49B7A72');
        $this->addSql('DROP INDEX IDX_DAE4973A49B7A72 ON auto_spare_part_specific_advert');
        $this->addSql('ALTER TABLE auto_spare_part_specific_advert DROP spare_part_id');
    }
}
