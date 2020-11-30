<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130223902 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_file DROP FOREIGN KEY FK_7EA5DC8EBF396750');
        $this->addSql('ALTER TABLE image_url DROP FOREIGN KEY FK_AC9C95FDBF396750');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE image_file');
        $this->addSql('DROP TABLE image_url');
        $this->addSql('ALTER TABLE person ADD biography LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->throwIrreversibleMigrationException();
    }
}
