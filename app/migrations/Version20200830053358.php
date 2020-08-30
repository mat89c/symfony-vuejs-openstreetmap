<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200830053358 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE map_point_map_point_category (map_point_id INT NOT NULL, map_point_category_id INT NOT NULL, INDEX IDX_F7A92D32A2DAF885 (map_point_id), INDEX IDX_F7A92D3237769206 (map_point_category_id), PRIMARY KEY(map_point_id, map_point_category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE map_point_map_point_category ADD CONSTRAINT FK_F7A92D32A2DAF885 FOREIGN KEY (map_point_id) REFERENCES map_point (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE map_point_map_point_category ADD CONSTRAINT FK_F7A92D3237769206 FOREIGN KEY (map_point_category_id) REFERENCES map_point_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE map_point_map_point_category');
    }
}
