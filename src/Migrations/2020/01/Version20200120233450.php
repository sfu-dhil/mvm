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
final class Version20200120233450 extends AbstractMigration {
    public function getDescription() : string {
        return '';
    }

    public function up(Schema $schema) : void {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE wbnt_dfgb_test DROP FOREIGN KEY FK_A86F10518E63F6D6');
        $this->addSql('DROP TABLE wbnt_dfgb_test');
        $this->addSql('DROP TABLE wbnt_dfgb_testrelated');
        $this->addSql('CREATE TABLE element (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, label VARCHAR(120) NOT NULL, description LONGTEXT DEFAULT NULL, uri VARCHAR(190) NOT NULL, comment LONGTEXT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, FULLTEXT INDEX IDX_41405E39EA750E8 (label), FULLTEXT INDEX IDX_41405E396DE44026 (description), FULLTEXT INDEX IDX_41405E39EA750E86DE44026 (label, description), UNIQUE INDEX UNIQ_41405E39841CB121 (uri), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, label VARCHAR(120) NOT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, FULLTEXT INDEX IDX_B1133D0EEA750E8 (label), FULLTEXT INDEX IDX_B1133D0E6DE44026 (description), FULLTEXT INDEX IDX_B1133D0EEA750E86DE44026 (label, description), UNIQUE INDEX UNIQ_B1133D0E5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment_note (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, comment_id INT NOT NULL, content LONGTEXT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_E98B58F8A76ED395 (user_id), INDEX IDX_E98B58F8F8697D13 (comment_id), FULLTEXT INDEX commentnote_ft_idx (content), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, fullname VARCHAR(120) NOT NULL, email VARCHAR(120) NOT NULL, follow_up TINYINT(1) NOT NULL, entity VARCHAR(120) NOT NULL, content LONGTEXT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_9474526C6BF700BD (status_id), FULLTEXT INDEX comment_ft_idx (fullname, content), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_note ADD CONSTRAINT FK_E98B58F8A76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE comment_note ADD CONSTRAINT FK_E98B58F8F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C6BF700BD FOREIGN KEY (status_id) REFERENCES comment_status (id)');

        $this->addSql('DROP INDEX UNIQ_5BA994A192FC23A8 ON nines_user');
        $this->addSql('DROP INDEX UNIQ_5BA994A1A0D96FBF ON nines_user');
        $this->addSql('DROP INDEX UNIQ_5BA994A1C05FB297 ON nines_user');

        $this->addSql(
            <<<'ENDSQL'
ALTER TABLE nines_user
    DROP username_canonical, 
    DROP email_canonical, 
    DROP email,
    DROP salt,
    DROP data,

	CHANGE fullname fullname varchar(64) NOT NULL,
	CHANGE enabled active TINYINT NOT NULL DEFAULT 0,
 	CHANGE COLUMN username email VARCHAR(180) NOT NULL,
    CHANGE COLUMN confirmation_token reset_token VARCHAR(180) DEFAULT NULL,
    CHANGE COLUMN password_requested_at reset_expiry DATETIME DEFAULT NULL,
    CHANGE COLUMN institution affiliation VARCHAR(255) DEFAULT NULL,
    CHANGE COLUMN last_login login DATETIME DEFAULT NULL,
    
    ADD created DATETIME NOT NULL DEFAULT NOW() COMMENT '(DC2Type:datetime_immutable)', 
    ADD updated DATETIME NOT NULL DEFAULT NOW() COMMENT '(DC2Type:datetime_immutable)'    
;
ENDSQL
        );

        $this->addSql('CREATE UNIQUE INDEX UNIQ_5BA994A1E7927C74 ON nines_user (email)');
    }

    public function down(Schema $schema) : void {
        $this->throwIrreversibleMigrationException();
    }
}
