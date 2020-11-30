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
final class Version20201112231027 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_note DROP FOREIGN KEY FK_E98B58F8F8697D13');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C6BF700BD');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comment_note');
        $this->addSql('DROP TABLE comment_status');
        $this->addSql('DROP TABLE element');
        $this->addSql('CREATE TABLE coterie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, label VARCHAR(120) NOT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, FULLTEXT INDEX IDX_A88CB0EA750E8 (label), FULLTEXT INDEX IDX_A88CB06DE44026 (description), FULLTEXT INDEX IDX_A88CB0EA750E86DE44026 (label, description), UNIQUE INDEX UNIQ_A88CB05E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coterie_person (coterie_id INT NOT NULL, person_id INT NOT NULL, INDEX IDX_C4BF79A1EC24C7BD (coterie_id), INDEX IDX_C4BF79A1217BBB47 (person_id), PRIMARY KEY(coterie_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coterie_person ADD CONSTRAINT FK_C4BF79A1EC24C7BD FOREIGN KEY (coterie_id) REFERENCES coterie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coterie_person ADD CONSTRAINT FK_C4BF79A1217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void {
        $this->throwIrreversibleMigrationException();
    }
}
