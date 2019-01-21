<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190121191308 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE email_domain (id INT AUTO_INCREMENT NOT NULL, page_id INT DEFAULT NULL, email_end VARCHAR(128) NOT NULL, domain VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_DA33CDFB893813F7 (email_end), INDEX IDX_DA33CDFBC4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline VARCHAR(255) NOT NULL, text_bottom LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE email_domain ADD CONSTRAINT FK_DA33CDFBC4663E4 FOREIGN KEY (page_id) REFERENCES registration_page (id)');

        $this->addSql('INSERT INTO `registration_page` (`title`, `description`, `headline`, `text_bottom`) VALUES ("", "", "", "")');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE email_domain DROP FOREIGN KEY FK_DA33CDFBC4663E4');
        $this->addSql('DROP TABLE email_domain');
        $this->addSql('DROP TABLE registration_page');
    }
}
