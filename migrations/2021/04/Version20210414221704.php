<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210414221704 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manuscript_content DROP FOREIGN KEY FK_D956C594E75A3578');
        $this->addSql('ALTER TABLE manuscript_content ADD CONSTRAINT FK_D956C594E75A3578 FOREIGN KEY (print_source_id) REFERENCES print_source (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manuscript_content DROP FOREIGN KEY FK_D956C594E75A3578');
        $this->addSql('ALTER TABLE manuscript_content ADD CONSTRAINT FK_D956C594E75A3578 FOREIGN KEY (print_source_id) REFERENCES print_source (id)');
    }
}
