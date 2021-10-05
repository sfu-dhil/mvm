<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211005204838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX manuscript_ft ON manuscript');
        $this->addSql('CREATE FULLTEXT INDEX manuscript_ft ON manuscript (call_number, description, format)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX manuscript_ft ON manuscript');
        $this->addSql('CREATE FULLTEXT INDEX manuscript_ft ON manuscript (call_number, description)');
    }
}
