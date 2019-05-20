<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190517052802 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE obd2forum_comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, message_id INT DEFAULT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_2A5FC484A76ED395 (user_id), INDEX IDX_2A5FC484537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE obd2forum_message (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_896A697A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE obd2forum_message_technical_data (id INT AUTO_INCREMENT NOT NULL, brand_id INT DEFAULT NULL, model_id INT DEFAULT NULL, type_id INT DEFAULT NULL, code_id INT DEFAULT NULL, message_id INT DEFAULT NULL, INDEX IDX_C83A762344F5D008 (brand_id), INDEX IDX_C83A76237975B7E7 (model_id), INDEX IDX_C83A7623C54C8C93 (type_id), INDEX IDX_C83A762327DAFE17 (code_id), UNIQUE INDEX UNIQ_C83A7623537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE obd2forum_comment ADD CONSTRAINT FK_2A5FC484A76ED395 FOREIGN KEY (user_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE obd2forum_comment ADD CONSTRAINT FK_2A5FC484537A1329 FOREIGN KEY (message_id) REFERENCES obd2forum_message (id)');
        $this->addSql('ALTER TABLE obd2forum_message ADD CONSTRAINT FK_896A697A76ED395 FOREIGN KEY (user_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE obd2forum_message_technical_data ADD CONSTRAINT FK_C83A762344F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE obd2forum_message_technical_data ADD CONSTRAINT FK_C83A76237975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE obd2forum_message_technical_data ADD CONSTRAINT FK_C83A7623C54C8C93 FOREIGN KEY (type_id) REFERENCES type_obd2error (id)');
        $this->addSql('ALTER TABLE obd2forum_message_technical_data ADD CONSTRAINT FK_C83A762327DAFE17 FOREIGN KEY (code_id) REFERENCES code_obd2error (id)');
        $this->addSql('ALTER TABLE obd2forum_message_technical_data ADD CONSTRAINT FK_C83A7623537A1329 FOREIGN KEY (message_id) REFERENCES obd2forum_message (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE obd2forum_comment DROP FOREIGN KEY FK_2A5FC484537A1329');
        $this->addSql('ALTER TABLE obd2forum_message_technical_data DROP FOREIGN KEY FK_C83A7623537A1329');
        $this->addSql('DROP TABLE obd2forum_comment');
        $this->addSql('DROP TABLE obd2forum_message');
        $this->addSql('DROP TABLE obd2forum_message_technical_data');
    }
}
