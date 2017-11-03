<?php

namespace Proximum\Vimeet\Infrastructure\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add answer and question table schema
 */
class Version20171103131831 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE session_answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, title LONGTEXT NOT NULL, correct TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_B5B6407C1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session_question (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, title LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_3D5B2926613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE session_answer ADD CONSTRAINT FK_B5B6407C1E27F6BF FOREIGN KEY (question_id) REFERENCES session_question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE session_question ADD CONSTRAINT FK_3D5B2926613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE session_answer DROP FOREIGN KEY FK_B5B6407C1E27F6BF');
        $this->addSql('DROP TABLE session_answer');
        $this->addSql('DROP TABLE session_question');
    }
}
