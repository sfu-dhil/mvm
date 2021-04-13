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
final class Version20210208212140 extends AbstractMigration
{
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coterie_period (coterie_id INT NOT NULL, period_id INT NOT NULL, INDEX IDX_35DBB619EC24C7BD (coterie_id), INDEX IDX_35DBB619EC8B7ADE (period_id), PRIMARY KEY(coterie_id, period_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coterie_period ADD CONSTRAINT FK_35DBB619EC24C7BD FOREIGN KEY (coterie_id) REFERENCES coterie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coterie_period ADD CONSTRAINT FK_35DBB619EC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE coterie_period');
    }
}
