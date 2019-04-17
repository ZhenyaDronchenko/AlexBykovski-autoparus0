<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190417200911 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE universal_page_brand_image DROP FOREIGN KEY FK_A46E03EE3DA5256D');
        $this->addSql('ALTER TABLE universal_page_brand_image ADD CONSTRAINT FK_A46E03EE3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE universal_page_city_image DROP FOREIGN KEY FK_9928CB693DA5256D');
        $this->addSql('ALTER TABLE universal_page_city_image ADD CONSTRAINT FK_9928CB693DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE universal_page_spare_part_image DROP FOREIGN KEY FK_A1F3CD7E3DA5256D');
        $this->addSql('ALTER TABLE universal_page_spare_part_image ADD CONSTRAINT FK_A1F3CD7E3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE universal_page_brand_image DROP FOREIGN KEY FK_A46E03EE3DA5256D');
        $this->addSql('ALTER TABLE universal_page_brand_image ADD CONSTRAINT FK_A46E03EE3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE universal_page_city_image DROP FOREIGN KEY FK_9928CB693DA5256D');
        $this->addSql('ALTER TABLE universal_page_city_image ADD CONSTRAINT FK_9928CB693DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE universal_page_spare_part_image DROP FOREIGN KEY FK_A1F3CD7E3DA5256D');
        $this->addSql('ALTER TABLE universal_page_spare_part_image ADD CONSTRAINT FK_A1F3CD7E3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
    }
}
