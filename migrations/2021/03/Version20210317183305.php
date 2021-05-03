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
final class Version20210317183305 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_page CHANGE in_menu in_menu TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX content_ft ON content');
        $this->addSql('CREATE FULLTEXT INDEX content_ft ON content (first_line, transcription)');
        $this->addSql('CREATE FULLTEXT INDEX IDX_C53D045F545615306DE44026 ON image (original_name, description)');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_page CHANGE in_menu in_menu TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('DROP INDEX content_ft ON content');
        $this->addSql('CREATE FULLTEXT INDEX content_ft ON content (title, first_line, transcription)');
        $this->addSql('DROP INDEX IDX_C53D045F545615306DE44026 ON image');
    }
}
