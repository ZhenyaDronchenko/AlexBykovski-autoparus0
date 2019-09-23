<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Article\ArticleType;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190922193702 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->connection->exec('CREATE TABLE article_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->connection->exec('CREATE TABLE article_details_types (detail_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_4E04C07AD8D003BB (detail_id), INDEX IDX_4E04C07AC54C8C93 (type_id), PRIMARY KEY(detail_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->connection->exec('ALTER TABLE article_details_types ADD CONSTRAINT FK_4E04C07AD8D003BB FOREIGN KEY (detail_id) REFERENCES article_detail (id)');
        $this->connection->exec('ALTER TABLE article_details_types ADD CONSTRAINT FK_4E04C07AC54C8C93 FOREIGN KEY (type_id) REFERENCES article_type (id)');

        foreach (ArticleType::TYPES as $type => $name) {
            $this->connection->exec("INSERT INTO article_type (`type`, `name`) VALUES ('" . $type . "', '" . $name . "')");
        }

        $this->addSql('
          INSERT INTO article_details_types (detail_id, type_id)
          SELECT ad.id, `at`.id
            FROM article_detail AS ad
            INNER JOIN article AS art ON art.id = ad.article_id
            INNER JOIN article_type AS `at` WHERE `at`.type = :type AND art.is_our = :trueValue', [
            "type" => ArticleType::OUR_UNIQUE_MATERIAL,
            "trueValue" => true,
        ]);

        $this->addSql('ALTER TABLE article ADD activate_at DATETIME DEFAULT NULL, DROP is_our');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article ADD is_our TINYINT(1) DEFAULT \'0\' NOT NULL, DROP activate_at');

        $this->addSql('
          UPDATE article AS art
            INNER JOIN article_detail AS ad ON art.id = ad.article_id
            INNER JOIN article_details_types AS adt ON adt.detail_id = ad.id
            INNER JOIN article_type AS `at` ON adt.type_id = `at`.id
            SET art.is_our = :trueValue
            WHERE `at`.type = :type', [
            "type" => ArticleType::OUR_UNIQUE_MATERIAL,
            "trueValue" => true,
        ]);

        $this->addSql('ALTER TABLE article_details_types DROP FOREIGN KEY FK_4E04C07AC54C8C93');
        $this->addSql('DROP TABLE article_type');
        $this->addSql('DROP TABLE article_details_types');
    }
}
