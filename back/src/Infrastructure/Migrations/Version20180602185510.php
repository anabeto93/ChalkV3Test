<?php

namespace Proximum\Vimeet\Infrastructure\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180602185510 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE course ADD institution_id INT DEFAULT NULL, DROP university');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB910405986 FOREIGN KEY (institution_id) REFERENCES institution (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_169E6FB910405986 ON course (institution_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB910405986');
        $this->addSql('DROP INDEX IDX_169E6FB910405986 ON course');
        $this->addSql('ALTER TABLE course ADD university VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP institution_id');
    }
}
