<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181029202104 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE auto_spare_part_general_advert (id INT AUTO_INCREMENT NOT NULL, brand_id INT DEFAULT NULL, seller_advert_detail_id INT DEFAULT NULL, `condition` VARCHAR(255) NOT NULL, stock_type VARCHAR(255) NOT NULL, INDEX IDX_A658CE6D44F5D008 (brand_id), INDEX IDX_A658CE6D18291EB8 (seller_advert_detail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auto_spare_part_general_adverts_models (advert_id INT NOT NULL, model_id INT NOT NULL, INDEX IDX_F02F0FED07ECCB6 (advert_id), INDEX IDX_F02F0FE7975B7E7 (model_id), PRIMARY KEY(advert_id, model_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auto_spare_part_general_adverts_spare_parts (advert_id INT NOT NULL, spare_part_id INT NOT NULL, INDEX IDX_6F045F8FD07ECCB6 (advert_id), INDEX IDX_6F045F8F49B7A72 (spare_part_id), PRIMARY KEY(advert_id, spare_part_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seller_advert_detail (id INT AUTO_INCREMENT NOT NULL, seller_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_DE5460448DE820D9 (seller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE auto_spare_part_general_advert ADD CONSTRAINT FK_A658CE6D44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE auto_spare_part_general_advert ADD CONSTRAINT FK_A658CE6D18291EB8 FOREIGN KEY (seller_advert_detail_id) REFERENCES seller_advert_detail (id)');
        $this->addSql('ALTER TABLE auto_spare_part_general_adverts_models ADD CONSTRAINT FK_F02F0FED07ECCB6 FOREIGN KEY (advert_id) REFERENCES auto_spare_part_general_advert (id)');
        $this->addSql('ALTER TABLE auto_spare_part_general_adverts_models ADD CONSTRAINT FK_F02F0FE7975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE auto_spare_part_general_adverts_spare_parts ADD CONSTRAINT FK_6F045F8FD07ECCB6 FOREIGN KEY (advert_id) REFERENCES auto_spare_part_general_advert (id)');
        $this->addSql('ALTER TABLE auto_spare_part_general_adverts_spare_parts ADD CONSTRAINT FK_6F045F8F49B7A72 FOREIGN KEY (spare_part_id) REFERENCES spare_part (id)');
        $this->addSql('ALTER TABLE seller_advert_detail ADD CONSTRAINT FK_DE5460448DE820D9 FOREIGN KEY (seller_id) REFERENCES seller_data (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auto_spare_part_general_adverts_models DROP FOREIGN KEY FK_F02F0FED07ECCB6');
        $this->addSql('ALTER TABLE auto_spare_part_general_adverts_spare_parts DROP FOREIGN KEY FK_6F045F8FD07ECCB6');
        $this->addSql('ALTER TABLE auto_spare_part_general_advert DROP FOREIGN KEY FK_A658CE6D18291EB8');
        $this->addSql('DROP TABLE auto_spare_part_general_advert');
        $this->addSql('DROP TABLE auto_spare_part_general_adverts_models');
        $this->addSql('DROP TABLE auto_spare_part_general_adverts_spare_parts');
        $this->addSql('DROP TABLE seller_advert_detail');
    }
}
