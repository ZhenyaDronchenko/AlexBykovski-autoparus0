<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190204210429 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_obd2error_code (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, user_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, counter INT DEFAULT 0 NOT NULL, INDEX IDX_8562C22CC54C8C93 (type_id), INDEX IDX_8562C22CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_obd2error_code ADD CONSTRAINT FK_8562C22CC54C8C93 FOREIGN KEY (type_id) REFERENCES type_obd2error (id)');
        $this->addSql('ALTER TABLE user_obd2error_code ADD CONSTRAINT FK_8562C22CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE code_obd2error ADD counter INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_obd2error_code');
        $this->addSql('ALTER TABLE code_obd2error DROP counter, CHANGE active active TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_often_search is_often_search TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
