<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190116212319 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE code_obd2error (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, transcript_ru VARCHAR(255) NOT NULL, transcript_en VARCHAR(255) NOT NULL, reason LONGTEXT NOT NULL, advice LONGTEXT NOT NULL, url_to_catalog VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, is_often_search TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_F6EC0019C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_obd2error (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, designation VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE code_obd2error ADD CONSTRAINT FK_F6EC0019C54C8C93 FOREIGN KEY (type_id) REFERENCES type_obd2error (id)');

        $this->addSql('INSERT INTO `type_obd2error` (`type`, `url`, `designation`, `description`) VALUES ("", "", "", ""), ("", "", "", ""), ("", "", "", ""), ("", "", "", "")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE code_obd2error DROP FOREIGN KEY FK_F6EC0019C54C8C93');
        $this->addSql('DROP TABLE code_obd2error');
        $this->addSql('DROP TABLE type_obd2error');
    }
}
