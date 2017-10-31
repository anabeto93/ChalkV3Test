<?php

namespace Proximum\Vimeet\Infrastructure\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * First migration to create table for course, folder, session, user, file, progression
 */
class Version20171031154131 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, teacher_name VARCHAR(255) NOT NULL, university VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, size INT NOT NULL, UNIQUE INDEX UNIQ_169E6FB9D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE folder (id INT AUTO_INCREMENT NOT NULL, course_id INT NOT NULL, uuid VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, size INT NOT NULL, UNIQUE INDEX UNIQ_ECA209CDD17F50A6 (uuid), INDEX IDX_ECA209CD591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session_file (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, path VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, size INT NOT NULL, INDEX IDX_4AEE363A613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, folder_id INT DEFAULT NULL, course_id INT NOT NULL, uuid VARCHAR(255) NOT NULL, rank INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, need_validation TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, content_updated_at DATETIME NOT NULL, size INT NOT NULL, content_size INT NOT NULL, UNIQUE INDEX UNIQ_D044D5D4D17F50A6 (uuid), INDEX IDX_D044D5D4162CB942 (folder_id), INDEX IDX_D044D5D4591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE upload_file (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_progression (user_id INT NOT NULL, session_id INT NOT NULL, medium VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7CA999E6A76ED395 (user_id), INDEX IDX_7CA999E6613FECDF (session_id), PRIMARY KEY(user_id, session_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, size INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, last_login_access_notification_at DATETIME DEFAULT NULL, api_token VARCHAR(255) NOT NULL, force_update TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), UNIQUE INDEX UNIQ_8D93D6496B01BC5B (phone_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_course (user_id INT NOT NULL, course_id INT NOT NULL, assigned_at DATETIME NOT NULL, INDEX IDX_73CC7484A76ED395 (user_id), INDEX IDX_73CC7484591CC992 (course_id), PRIMARY KEY(user_id, course_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE folder ADD CONSTRAINT FK_ECA209CD591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session_file ADD CONSTRAINT FK_4AEE363A613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_progression ADD CONSTRAINT FK_7CA999E6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_progression ADD CONSTRAINT FK_7CA999E6613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT FK_73CC7484A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_course ADD CONSTRAINT FK_73CC7484591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CD591CC992');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4591CC992');
        $this->addSql('ALTER TABLE user_course DROP FOREIGN KEY FK_73CC7484591CC992');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4162CB942');
        $this->addSql('ALTER TABLE session_file DROP FOREIGN KEY FK_4AEE363A613FECDF');
        $this->addSql('ALTER TABLE user_progression DROP FOREIGN KEY FK_7CA999E6613FECDF');
        $this->addSql('ALTER TABLE user_progression DROP FOREIGN KEY FK_7CA999E6A76ED395');
        $this->addSql('ALTER TABLE user_course DROP FOREIGN KEY FK_73CC7484A76ED395');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE folder');
        $this->addSql('DROP TABLE session_file');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE upload_file');
        $this->addSql('DROP TABLE user_progression');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_course');
    }
}
