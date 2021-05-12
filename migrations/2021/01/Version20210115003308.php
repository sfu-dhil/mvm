<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210115003308 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coterie_manuscript (coterie_id INT NOT NULL, manuscript_id INT NOT NULL, INDEX IDX_C57C5E4DEC24C7BD (coterie_id), INDEX IDX_C57C5E4DA05723D9 (manuscript_id), PRIMARY KEY(coterie_id, manuscript_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coterie_region (coterie_id INT NOT NULL, region_id INT NOT NULL, INDEX IDX_FF0159A1EC24C7BD (coterie_id), INDEX IDX_FF0159A198260155 (region_id), PRIMARY KEY(coterie_id, region_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coterie_manuscript ADD CONSTRAINT FK_C57C5E4DEC24C7BD FOREIGN KEY (coterie_id) REFERENCES coterie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coterie_manuscript ADD CONSTRAINT FK_C57C5E4DA05723D9 FOREIGN KEY (manuscript_id) REFERENCES manuscript (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coterie_region ADD CONSTRAINT FK_FF0159A1EC24C7BD FOREIGN KEY (coterie_id) REFERENCES coterie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coterie_region ADD CONSTRAINT FK_FF0159A198260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE coterie_manuscript');
        $this->addSql('DROP TABLE coterie_region');
    }
}
