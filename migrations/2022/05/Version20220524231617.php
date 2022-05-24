<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220524231617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE citation');
        $this->addSql('ALTER TABLE archive CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE content_role CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE coterie CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE feature CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE manuscript_role CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_page DROP FOREIGN KEY FK_F4DA3AB0A76ED395');
        $this->addSql('DROP INDEX idx_f4da3ab0a76ed395 ON nines_blog_page');
        $this->addSql('CREATE INDEX IDX_23FD24C7A76ED395 ON nines_blog_page (user_id)');
        $this->addSql('DROP INDEX blog_page_content ON nines_blog_page');
        $this->addSql('CREATE FULLTEXT INDEX blog_page_ft ON nines_blog_page (title, searchable)');
        $this->addSql('ALTER TABLE nines_blog_page ADD CONSTRAINT FK_F4DA3AB0A76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_BA5AE01D12469DE2');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_BA5AE01DA76ED395');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_BA5AE01D6BF700BD');
        $this->addSql('DROP INDEX idx_ba5ae01d12469de2 ON nines_blog_post');
        $this->addSql('CREATE INDEX IDX_6D7DFE6A12469DE2 ON nines_blog_post (category_id)');
        $this->addSql('DROP INDEX idx_ba5ae01d6bf700bd ON nines_blog_post');
        $this->addSql('CREATE INDEX IDX_6D7DFE6A6BF700BD ON nines_blog_post (status_id)');
        $this->addSql('DROP INDEX idx_ba5ae01da76ed395 ON nines_blog_post');
        $this->addSql('CREATE INDEX IDX_6D7DFE6AA76ED395 ON nines_blog_post (user_id)');
        $this->addSql('DROP INDEX blog_post_content ON nines_blog_post');
        $this->addSql('CREATE FULLTEXT INDEX blog_post_ft ON nines_blog_post (title, searchable)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_BA5AE01D12469DE2 FOREIGN KEY (category_id) REFERENCES nines_blog_post_category (id)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_BA5AE01DA76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_BA5AE01D6BF700BD FOREIGN KEY (status_id) REFERENCES nines_blog_post_status (id)');
        $this->addSql('ALTER TABLE nines_blog_post_category CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('DROP INDEX idx_ca275a0cea750e8 ON nines_blog_post_category');
        $this->addSql('CREATE FULLTEXT INDEX IDX_32F5FC8CEA750E8 ON nines_blog_post_category (label)');
        $this->addSql('DROP INDEX idx_ca275a0c6de44026 ON nines_blog_post_category');
        $this->addSql('CREATE FULLTEXT INDEX IDX_32F5FC8C6DE44026 ON nines_blog_post_category (description)');
        $this->addSql('DROP INDEX idx_ca275a0cea750e86de44026 ON nines_blog_post_category');
        $this->addSql('CREATE FULLTEXT INDEX IDX_32F5FC8CEA750E86DE44026 ON nines_blog_post_category (label, description)');
        $this->addSql('DROP INDEX uniq_ca275a0c5e237e06 ON nines_blog_post_category');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32F5FC8C5E237E06 ON nines_blog_post_category (name)');
        $this->addSql('ALTER TABLE nines_blog_post_status CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('DROP INDEX idx_92121d87ea750e8 ON nines_blog_post_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_4A63E2FDEA750E8 ON nines_blog_post_status (label)');
        $this->addSql('DROP INDEX idx_92121d876de44026 ON nines_blog_post_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_4A63E2FD6DE44026 ON nines_blog_post_status (description)');
        $this->addSql('DROP INDEX idx_92121d87ea750e86de44026 ON nines_blog_post_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_4A63E2FDEA750E86DE44026 ON nines_blog_post_status (label, description)');
        $this->addSql('DROP INDEX uniq_92121d875e237e06 ON nines_blog_post_status');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A63E2FD5E237E06 ON nines_blog_post_status (name)');
        $this->addSql('ALTER TABLE nines_media_image CHANGE original_name original_name VARCHAR(128) NOT NULL, CHANGE entity entity VARCHAR(120) NOT NULL, CHANGE image_path path VARCHAR(128) NOT NULL, CHANGE image_size file_size INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_4055C59BE284468 ON nines_media_image (entity)');
        $this->addSql('DROP INDEX idx_c53d045f545615306de44026 ON nines_media_image');
        $this->addSql('CREATE FULLTEXT INDEX nines_media_image_ft ON nines_media_image (original_name, description)');
        $this->addSql('ALTER TABLE nines_media_link CHANGE entity entity VARCHAR(120) NOT NULL, CHANGE text text VARCHAR(191) DEFAULT NULL');
        $this->addSql('DROP INDEX idx_36ac99f1f47645ae3b8ba7c7 ON nines_media_link');
        $this->addSql('CREATE FULLTEXT INDEX nines_media_link_ft ON nines_media_link (url, text)');
        $this->addSql('DROP INDEX idx_36ac99f1e284468 ON nines_media_link');
        $this->addSql('CREATE INDEX IDX_3B5D85A3E284468 ON nines_media_link (entity)');
        $this->addSql('ALTER TABLE period CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE print_source CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE theme CHANGE name name VARCHAR(191) NOT NULL, CHANGE label label VARCHAR(200) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE citation (id INT AUTO_INCREMENT NOT NULL, entity VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, citation LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FABD9C7EE284468 (entity), FULLTEXT INDEX IDX_FABD9C7EFABD9C7E6DE44026 (citation, description), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE archive CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE content_role CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE coterie CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE feature CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE manuscript_role CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nines_blog_page DROP FOREIGN KEY FK_23FD24C7A76ED395');
        $this->addSql('DROP INDEX idx_23fd24c7a76ed395 ON nines_blog_page');
        $this->addSql('CREATE INDEX IDX_F4DA3AB0A76ED395 ON nines_blog_page (user_id)');
        $this->addSql('DROP INDEX blog_page_ft ON nines_blog_page');
        $this->addSql('CREATE FULLTEXT INDEX blog_page_content ON nines_blog_page (title, searchable)');
        $this->addSql('ALTER TABLE nines_blog_page ADD CONSTRAINT FK_23FD24C7A76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_6D7DFE6A12469DE2');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_6D7DFE6A6BF700BD');
        $this->addSql('ALTER TABLE nines_blog_post DROP FOREIGN KEY FK_6D7DFE6AA76ED395');
        $this->addSql('DROP INDEX idx_6d7dfe6aa76ed395 ON nines_blog_post');
        $this->addSql('CREATE INDEX IDX_BA5AE01DA76ED395 ON nines_blog_post (user_id)');
        $this->addSql('DROP INDEX blog_post_ft ON nines_blog_post');
        $this->addSql('CREATE FULLTEXT INDEX blog_post_content ON nines_blog_post (title, searchable)');
        $this->addSql('DROP INDEX idx_6d7dfe6a12469de2 ON nines_blog_post');
        $this->addSql('CREATE INDEX IDX_BA5AE01D12469DE2 ON nines_blog_post (category_id)');
        $this->addSql('DROP INDEX idx_6d7dfe6a6bf700bd ON nines_blog_post');
        $this->addSql('CREATE INDEX IDX_BA5AE01D6BF700BD ON nines_blog_post (status_id)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_6D7DFE6A12469DE2 FOREIGN KEY (category_id) REFERENCES nines_blog_post_category (id)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_6D7DFE6A6BF700BD FOREIGN KEY (status_id) REFERENCES nines_blog_post_status (id)');
        $this->addSql('ALTER TABLE nines_blog_post ADD CONSTRAINT FK_6D7DFE6AA76ED395 FOREIGN KEY (user_id) REFERENCES nines_user (id)');
        $this->addSql('ALTER TABLE nines_blog_post_category CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX idx_32f5fc8c6de44026 ON nines_blog_post_category');
        $this->addSql('CREATE FULLTEXT INDEX IDX_CA275A0C6DE44026 ON nines_blog_post_category (description)');
        $this->addSql('DROP INDEX idx_32f5fc8cea750e86de44026 ON nines_blog_post_category');
        $this->addSql('CREATE FULLTEXT INDEX IDX_CA275A0CEA750E86DE44026 ON nines_blog_post_category (label, description)');
        $this->addSql('DROP INDEX uniq_32f5fc8c5e237e06 ON nines_blog_post_category');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CA275A0C5E237E06 ON nines_blog_post_category (name)');
        $this->addSql('DROP INDEX idx_32f5fc8cea750e8 ON nines_blog_post_category');
        $this->addSql('CREATE FULLTEXT INDEX IDX_CA275A0CEA750E8 ON nines_blog_post_category (label)');
        $this->addSql('ALTER TABLE nines_blog_post_status CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('DROP INDEX idx_4a63e2fd6de44026 ON nines_blog_post_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_92121D876DE44026 ON nines_blog_post_status (description)');
        $this->addSql('DROP INDEX idx_4a63e2fdea750e86de44026 ON nines_blog_post_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_92121D87EA750E86DE44026 ON nines_blog_post_status (label, description)');
        $this->addSql('DROP INDEX uniq_4a63e2fd5e237e06 ON nines_blog_post_status');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_92121D875E237E06 ON nines_blog_post_status (name)');
        $this->addSql('DROP INDEX idx_4a63e2fdea750e8 ON nines_blog_post_status');
        $this->addSql('CREATE FULLTEXT INDEX IDX_92121D87EA750E8 ON nines_blog_post_status (label)');
        $this->addSql('DROP INDEX IDX_4055C59BE284468 ON nines_media_image');
        $this->addSql('ALTER TABLE nines_media_image CHANGE entity entity VARCHAR(80) NOT NULL, CHANGE original_name original_name VARCHAR(64) NOT NULL, CHANGE path image_path VARCHAR(128) NOT NULL, CHANGE file_size image_size INT NOT NULL');
        $this->addSql('DROP INDEX nines_media_image_ft ON nines_media_image');
        $this->addSql('CREATE FULLTEXT INDEX IDX_C53D045F545615306DE44026 ON nines_media_image (original_name, description)');
        $this->addSql('ALTER TABLE nines_media_link CHANGE text text VARCHAR(200) DEFAULT NULL, CHANGE entity entity VARCHAR(128) NOT NULL');
        $this->addSql('DROP INDEX idx_3b5d85a3e284468 ON nines_media_link');
        $this->addSql('CREATE INDEX IDX_36AC99F1E284468 ON nines_media_link (entity)');
        $this->addSql('DROP INDEX nines_media_link_ft ON nines_media_link');
        $this->addSql('CREATE FULLTEXT INDEX IDX_36AC99F1F47645AE3B8BA7C7 ON nines_media_link (url, text)');
        $this->addSql('ALTER TABLE period CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE print_source CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE theme CHANGE name name VARCHAR(120) NOT NULL, CHANGE label label VARCHAR(120) NOT NULL');
    }
}
