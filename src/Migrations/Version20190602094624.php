<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190602094624 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66E4873418');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66E4873418 FOREIGN KEY (main_image_id) REFERENCES article_image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_banner DROP FOREIGN KEY FK_55A2F677294869C');
        $this->addSql('ALTER TABLE article_banner ADD CONSTRAINT FK_55A2F677294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_image DROP FOREIGN KEY FK_B28A764E7294869C');
        $this->addSql('ALTER TABLE article_image ADD CONSTRAINT FK_B28A764E7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66E4873418');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66E4873418 FOREIGN KEY (main_image_id) REFERENCES article_image (id)');
        $this->addSql('ALTER TABLE article_banner DROP FOREIGN KEY FK_55A2F677294869C');
        $this->addSql('ALTER TABLE article_banner ADD CONSTRAINT FK_55A2F677294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_image DROP FOREIGN KEY FK_B28A764E7294869C');
        $this->addSql('ALTER TABLE article_image ADD CONSTRAINT FK_B28A764E7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
    }
}
