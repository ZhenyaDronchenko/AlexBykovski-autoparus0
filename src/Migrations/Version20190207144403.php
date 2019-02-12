<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190207144403 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE seller_company ADD is_spare_part_seller TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_auto_seller TINYINT(1) DEFAULT \'0\' NOT NULL, ADD additional_phone2 VARCHAR(255) DEFAULT NULL, ADD additional_phone3 VARCHAR(255) DEFAULT NULL');

        $this->addSql('UPDATE seller_company SET is_spare_part_seller = is_seller');

        $this->addSql('ALTER TABLE seller_company DROP is_seller');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE seller_company ADD is_seller TINYINT(1) DEFAULT \'0\' NOT NULL');

        $this->addSql('UPDATE seller_company SET is_seller = is_spare_part_seller');

        $this->addSql('ALTER TABLE seller_company DROP is_spare_part_seller, DROP is_auto_seller, DROP additional_phone2, DROP additional_phone3');
    }
}
