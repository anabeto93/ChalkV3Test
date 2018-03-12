<?php

namespace Proximum\Vimeet\Infrastructure\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180312135237 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE institution (id INT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, size INT NOT NULL, UNIQUE INDEX UNIQ_3A9F98E5D17F50A6 (uuid), UNIQUE INDEX UNIQ_3A9F98E55E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_institution (user_id INT NOT NULL, institution_id INT NOT NULL, assigned_at DATETIME NOT NULL, INDEX IDX_93845170A76ED395 (user_id), INDEX IDX_9384517010405986 (institution_id), PRIMARY KEY(user_id, institution_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_institution ADD CONSTRAINT FK_93845170A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_institution ADD CONSTRAINT FK_9384517010405986 FOREIGN KEY (institution_id) REFERENCES institution (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_institution DROP FOREIGN KEY FK_9384517010405986');
        $this->addSql('DROP TABLE institution');
        $this->addSql('DROP TABLE user_institution');
    }
}
