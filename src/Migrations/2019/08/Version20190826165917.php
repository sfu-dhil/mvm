<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190826165917 extends AbstractMigration {
    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person ADD gender VARCHAR(1) DEFAULT NULL AFTER sortable_name');
        $this->addSql('DROP INDEX content_title_idx ON content');
        $this->addSql('DROP INDEX content_ft ON content');
        $this->addSql('ALTER TABLE content ADD first_line VARCHAR(255) NOT NULL AFTER print_source_id, CHANGE title title VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE INDEX content_firstline_idx ON content (first_line)');
        $this->addSql('CREATE FULLTEXT INDEX content_ft ON content (title, first_line, transcription)');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX content_firstline_idx ON content');
        $this->addSql('DROP INDEX content_ft ON content');
        $this->addSql('ALTER TABLE content DROP first_line, CHANGE title title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('CREATE INDEX content_title_idx ON content (title(191))');
        $this->addSql('CREATE FULLTEXT INDEX content_ft ON content (title, transcription)');
        $this->addSql('ALTER TABLE person DROP gender');
    }
}
