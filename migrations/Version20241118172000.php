<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118172000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD worker_id VARCHAR(36) DEFAULT NULL, DROP worker');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB256B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id)');
        $this->addSql('CREATE INDEX IDX_527EDB256B20BA36 ON task (worker_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB256B20BA36');
        $this->addSql('DROP INDEX IDX_527EDB256B20BA36 ON task');
        $this->addSql('ALTER TABLE task ADD worker VARCHAR(255) DEFAULT NULL, DROP worker_id');
    }
}
