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
final class Version20210113003643 extends AbstractMigration
{
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX manuscript_title_idx ON manuscript');
        $this->addSql('DROP INDEX manuscript_ft ON manuscript');
        $this->addSql('CREATE FULLTEXT INDEX manuscript_ft ON manuscript (call_number, description)');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX manuscript_ft ON manuscript');
        $this->addSql('CREATE INDEX manuscript_title_idx ON manuscript (title)');
        $this->addSql('CREATE FULLTEXT INDEX manuscript_ft ON manuscript (title, call_number, description)');
    }
}
