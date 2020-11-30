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
final class Version20190823214545 extends AbstractMigration {
    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE manuscript CHANGE filled_page_count filled_page_count VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE INDEX region_name_idx ON region (name)');
        $this->addSql('CREATE INDEX content_title_idx ON content (title)');
        $this->addSql('CREATE INDEX manuscript_title_idx ON manuscript (title)');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE manuscript CHANGE filled_page_count filled_page_count INT DEFAULT NULL');
        $this->addSql('DROP INDEX content_title_idx ON content');
        $this->addSql('DROP INDEX manuscript_title_idx ON manuscript');
        $this->addSql('DROP INDEX region_name_idx ON region');
    }
}
