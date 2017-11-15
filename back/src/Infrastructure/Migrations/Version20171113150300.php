<?php

namespace Proximum\Vimeet\Infrastructure\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add user_session_quiz_result
 */
class Version20171113150300 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_session_quiz_result (user_id INT NOT NULL, session_id INT NOT NULL, correct_answers_number INT NOT NULL, questions_number INT NOT NULL, questions_result LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', medium VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_28B4404FA76ED395 (user_id), INDEX IDX_28B4404F613FECDF (session_id), PRIMARY KEY(user_id, session_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_session_quiz_result ADD CONSTRAINT FK_28B4404FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_session_quiz_result ADD CONSTRAINT FK_28B4404F613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_session_quiz_result');
    }
}
