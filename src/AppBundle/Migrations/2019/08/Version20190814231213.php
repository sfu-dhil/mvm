<?php declare(strict_types=1);

namespace AppBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190814231213 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE manuscript CHANGE bibliography bibliography LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE manuscript CHANGE call_number call_number LONGTEXT DEFAULT NULL');
        $this->addSql('DROP TABLE IF EXISTS abstract_source');
        $this->addSql('ALTER TABLE person ADD anonymous TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE manuscript ADD untitled TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE manuscript CHANGE bibliography bibliography LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE manuscript CHANGE call_number call_number LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE manuscript DROP untitled');
        $this->addSql('ALTER TABLE person DROP anonymous');
    }
}
