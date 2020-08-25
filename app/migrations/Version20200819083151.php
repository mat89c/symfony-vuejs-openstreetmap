<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200819083151 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE map_point (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, lat VARCHAR(255) NOT NULL, lng VARCHAR(255) NOT NULL, color VARCHAR(10) NOT NULL, postcode VARCHAR(10) NOT NULL, city VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, upload_dir VARCHAR(255) NOT NULL, INDEX IDX_3753BC48A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE map_point_image (id INT AUTO_INCREMENT NOT NULL, map_point_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_81A5D68EA2DAF885 (map_point_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE map_point ADD CONSTRAINT FK_3753BC48A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE map_point_image ADD CONSTRAINT FK_81A5D68EA2DAF885 FOREIGN KEY (map_point_id) REFERENCES map_point (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE map_point_image DROP FOREIGN KEY FK_81A5D68EA2DAF885');
        $this->addSql('ALTER TABLE map_point DROP FOREIGN KEY FK_3753BC48A76ED395');
        $this->addSql('DROP TABLE map_point');
        $this->addSql('DROP TABLE map_point_image');
        $this->addSql('DROP TABLE user');
    }
}
