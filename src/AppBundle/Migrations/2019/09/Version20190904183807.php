<?php declare(strict_types=1);

namespace AppBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190904183807 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX manuscript_ft ON manuscript');
        $this->addSql('DROP INDEX manuscript_title_idx ON manuscript');
        $this->addSql('ALTER TABLE manuscript CHANGE title title VARCHAR(512) DEFAULT NULL');
        $this->addSql('CREATE INDEX manuscript_call_idx ON manuscript (call_number)');
        $this->addSql('CREATE FULLTEXT INDEX manuscript_ft ON manuscript (title, call_number, description)');
        $this->addSql('CREATE INDEX manuscript_title_idx ON manuscript (title)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX manuscript_call_idx ON manuscript');
        $this->addSql('DROP INDEX manuscript_ft ON manuscript');
        $this->addSql('DROP INDEX manuscript_title_idx ON manuscript');
        $this->addSql('ALTER TABLE manuscript CHANGE title title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('CREATE FULLTEXT INDEX manuscript_ft ON manuscript (title, description)');
        $this->addSql('CREATE INDEX manuscript_title_idx ON manuscript (title(191))');
    }
}
