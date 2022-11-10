<?php

declare(strict_types=1);

/*
 * (c) 2022 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220705221358 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE manuscript_majortheme (manuscript_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_8B9DEF13A05723D9 (manuscript_id), INDEX IDX_8B9DEF1359027487 (theme_id), PRIMARY KEY(manuscript_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manuscript_othertheme (manuscript_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_8F6A6023A05723D9 (manuscript_id), INDEX IDX_8F6A602359027487 (theme_id), PRIMARY KEY(manuscript_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE manuscript_majortheme ADD CONSTRAINT FK_8B9DEF13A05723D9 FOREIGN KEY (manuscript_id) REFERENCES manuscript (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE manuscript_majortheme ADD CONSTRAINT FK_8B9DEF1359027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE manuscript_othertheme ADD CONSTRAINT FK_8F6A6023A05723D9 FOREIGN KEY (manuscript_id) REFERENCES manuscript (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE manuscript_othertheme ADD CONSTRAINT FK_8F6A602359027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('INSERT INTO manuscript_majortheme SELECT * FROM manuscript_theme');
        $this->addSql('DROP TABLE manuscript_theme');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE manuscript_theme (manuscript_id INT NOT NULL, theme_id INT NOT NULL, INDEX IDX_478E014659027487 (theme_id), INDEX IDX_478E0146A05723D9 (manuscript_id), PRIMARY KEY(manuscript_id, theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE manuscript_theme ADD CONSTRAINT FK_478E014659027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE manuscript_theme ADD CONSTRAINT FK_478E0146A05723D9 FOREIGN KEY (manuscript_id) REFERENCES manuscript (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE manuscript_majortheme');
        $this->addSql('DROP TABLE manuscript_othertheme');
    }
}
