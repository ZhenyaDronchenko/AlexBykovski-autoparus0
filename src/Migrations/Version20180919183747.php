<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180919183747 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_car DROP FOREIGN KEY FK_9C2B871644F5D008');
        $this->addSql('ALTER TABLE user_car DROP FOREIGN KEY FK_9C2B87167975B7E7');
        $this->addSql('DROP INDEX IDX_9C2B871644F5D008 ON user_car');
        $this->addSql('DROP INDEX IDX_9C2B87167975B7E7 ON user_car');
        $this->addSql('ALTER TABLE user_car ADD brand VARCHAR(255) DEFAULT NULL, ADD model VARCHAR(255) DEFAULT NULL, DROP brand_id, DROP model_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_car ADD brand_id INT DEFAULT NULL, ADD model_id INT DEFAULT NULL, DROP brand, DROP model');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B871644F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE user_car ADD CONSTRAINT FK_9C2B87167975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('CREATE INDEX IDX_9C2B871644F5D008 ON user_car (brand_id)');
        $this->addSql('CREATE INDEX IDX_9C2B87167975B7E7 ON user_car (model_id)');
    }
}
