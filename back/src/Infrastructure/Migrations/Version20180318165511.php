<?php

namespace Proximum\Vimeet\Infrastructure\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180318165511 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cohort_course (cohort_id INT NOT NULL, course_id INT NOT NULL, assigned_at DATETIME NOT NULL, INDEX IDX_A8FE089635983C93 (cohort_id), INDEX IDX_A8FE0896591CC992 (course_id), PRIMARY KEY(cohort_id, course_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cohort_course ADD CONSTRAINT FK_A8FE089635983C93 FOREIGN KEY (cohort_id) REFERENCES cohort (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cohort_course ADD CONSTRAINT FK_A8FE0896591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE cohort_course');
    }
}
