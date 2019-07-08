<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190708100933 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_engine DROP FOREIGN KEY FK_8DFA01B0A39C27A5');
        $this->addSql('ALTER TABLE user_engine ADD CONSTRAINT FK_8DFA01B0A39C27A5 FOREIGN KEY (specific_advert_initiator_id) REFERENCES auto_spare_part_specific_advert (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_engine DROP FOREIGN KEY FK_8DFA01B0A39C27A5');
        $this->addSql('ALTER TABLE user_engine ADD CONSTRAINT FK_8DFA01B0A39C27A5 FOREIGN KEY (specific_advert_initiator_id) REFERENCES auto_spare_part_specific_advert (id)');
    }
}
