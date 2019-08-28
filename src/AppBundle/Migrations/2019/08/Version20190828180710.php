<?php declare(strict_types=1);

namespace AppBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190828180710 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE person_region (person_id INT NOT NULL, region_id INT NOT NULL, INDEX IDX_93C7C1C0217BBB47 (person_id), INDEX IDX_93C7C1C098260155 (region_id), PRIMARY KEY(person_id, region_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manuscript_period (manuscript_id INT NOT NULL, period_id INT NOT NULL, INDEX IDX_540C89BFA05723D9 (manuscript_id), INDEX IDX_540C89BFEC8B7ADE (period_id), PRIMARY KEY(manuscript_id, period_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person_region ADD CONSTRAINT FK_93C7C1C0217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_region ADD CONSTRAINT FK_93C7C1C098260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE manuscript_period ADD CONSTRAINT FK_540C89BFA05723D9 FOREIGN KEY (manuscript_id) REFERENCES manuscript (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE manuscript_period ADD CONSTRAINT FK_540C89BFEC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD1763E234BFB');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD1763FE1C430');
        $this->addSql('DROP INDEX IDX_34DCD1763FE1C430 ON person');
        $this->addSql('DROP INDEX IDX_34DCD1763E234BFB ON person');
        $this->addSql('ALTER TABLE person DROP birth_region_id, DROP death_region_id');
        $this->addSql('ALTER TABLE manuscript DROP FOREIGN KEY FK_5AF919CDEC8B7ADE');
        $this->addSql('DROP INDEX IDX_5AF919CDEC8B7ADE ON manuscript');
        $this->addSql('DROP INDEX manuscript_title_idx ON manuscript');
        $this->addSql('ALTER TABLE manuscript DROP period_id');
        $this->addSql('CREATE INDEX manuscript_title_idx ON manuscript (title)');
    }

    public function down(Schema $schema) : void
    {
        $this->throwIrreversibleMigrationException();
    }
}
