<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180908195255 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE catalog_brand_choice_brand (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, headline1 VARCHAR(255) NOT NULL, text1 LONGTEXT NOT NULL, headline2 VARCHAR(255) NOT NULL, text2 LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');

        $this->addSql('
            INSERT INTO catalog_brand_choice_brand (id, title, description, headline1, text1, headline2, text2)
            SELECT id, title, description, headline1, text1, headline2, text2 FROM catalog_general_page
         ');

        $this->addSql('DROP TABLE catalog_general_page');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE catalog_general_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, headline1 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text1 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, headline2 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text2 LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');

        $this->addSql('
            INSERT INTO catalog_general_page (id, title, description, headline1, text1, headline2, text2)
            SELECT id, title, description, headline1, text1, headline2, text2 FROM catalog_brand_choice_brand
         ');

        $this->addSql('DROP TABLE catalog_brand_choice_brand');
    }
}
