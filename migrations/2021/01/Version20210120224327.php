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
final class Version20210120224327 extends AbstractMigration
{
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE print_source_region (print_source_id INT NOT NULL, region_id INT NOT NULL, INDEX IDX_2EBC6DDBE75A3578 (print_source_id), INDEX IDX_2EBC6DDB98260155 (region_id), PRIMARY KEY(print_source_id, region_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE print_source_region ADD CONSTRAINT FK_2EBC6DDBE75A3578 FOREIGN KEY (print_source_id) REFERENCES print_source (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE print_source_region ADD CONSTRAINT FK_2EBC6DDB98260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE');
        $this->addSql('INSERT INTO print_source_region(print_source_id, region_id) SELECT id, region_id FROM print_source WHERE region_id IS NOT NULL');
        $this->addSql('ALTER TABLE print_source DROP FOREIGN KEY FK_534D01C198260155');
        $this->addSql('DROP INDEX IDX_534D01C198260155 ON print_source');
        $this->addSql('ALTER TABLE print_source DROP region_id');
    }

    public function down(Schema $schema) : void {
        $this->throwIrreversibleMigrationException();
    }
}
