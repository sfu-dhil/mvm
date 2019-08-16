<?php declare(strict_types=1);

namespace AppBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190816221209 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $manuscriptSql = <<<ENDSQL
ALTER TABLE manuscript 
  ADD first_line_index TINYINT(1) NOT NULL, 
  ADD digitized TINYINT(1) NOT NULL, 
  ADD format VARCHAR(255) DEFAULT NULL, 
  ADD size VARCHAR(255) DEFAULT NULL, 
  ADD complete TINYINT(1) NOT NULL, 
  DROP blank_page_count, 
  CHANGE filled_page_count filled_page_count INT DEFAULT NULL, 
  CHANGE item_count item_count INT DEFAULT NULL, 
  CHANGE poem_count poem_count INT DEFAULT NULL, 
  CHANGE call_number call_number VARCHAR(255) NOT NULL
ENDSQL;
        $this->addSql($manuscriptSql);

        $this->addSql('RENAME TABLE region region');
        $this->addSql('ALTER TABLE region RENAME COLUMN fullName TO name, DROP sortableName');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $manuscriptSql = <<<ENDSQL
ALTER TABLE manuscript 
  ADD blank_page_count INT NOT NULL, 
  DROP first_line_index, DROP digitized, 
  DROP format, 
  DROP size, 
  DROP complete, 
  CHANGE filled_page_count filled_page_count INT NOT NULL, 
  CHANGE item_count item_count INT NOT NULL, 
  CHANGE poem_count poem_count INT NOT NULL, 
  CHANGE call_number call_number VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci
ENDSQL;
        $this->addSql($manuscriptSql);

        $this->addSql('RENAME TABLE region region');
        $this->addSql('ALTER TABLE region RENAME COLUMN name to fullName, add fullName VARCHAR(255) DEFAULT NULL');
    }

}
