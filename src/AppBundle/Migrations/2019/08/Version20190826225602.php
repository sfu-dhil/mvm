<?php

declare(strict_types=1);

namespace AppBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190826225602 extends AbstractMigration {
    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE manuscript_content (id INT AUTO_INCREMENT NOT NULL, content_id INT NOT NULL, manuscript_id INT NOT NULL, print_source_id INT NOT NULL, context LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_D956C59484A0A3ED (content_id), INDEX IDX_D956C594A05723D9 (manuscript_id), INDEX IDX_D956C594E75A3578 (print_source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE manuscript_content ADD CONSTRAINT FK_D956C59484A0A3ED FOREIGN KEY (content_id) REFERENCES content (id)');
        $this->addSql('ALTER TABLE manuscript_content ADD CONSTRAINT FK_D956C594A05723D9 FOREIGN KEY (manuscript_id) REFERENCES manuscript (id)');
        $this->addSql('ALTER TABLE manuscript_content ADD CONSTRAINT FK_D956C594E75A3578 FOREIGN KEY (print_source_id) REFERENCES print_source (id)');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9A05723D9');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9E75A3578');
        $this->addSql('DROP INDEX IDX_FEC530A9A05723D9 ON content');
        $this->addSql('DROP INDEX IDX_FEC530A9E75A3578 ON content');
        $this->addSql('DROP INDEX content_firstline_idx ON content');
        $this->addSql('ALTER TABLE content DROP manuscript_id, DROP print_source_id, DROP context');
        $this->addSql('CREATE INDEX content_firstline_idx ON content (first_line)');
    }

    public function down(Schema $schema) : void {
        $this->throwIrreversibleMigrationException();
    }
}
