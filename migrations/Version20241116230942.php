<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241116230942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
         $this->addSql('CREATE TABLE task (
            id CHAR(36) NOT NULL PRIMARY KEY,
            status ENUM(\'NEW\', \'IN_PROGRESS\', \'COMPLETED\', \'FAILED\') NOT NULL, 
            name VARCHAR(255) DEFAULT NULL,
            created_at DATETIME NOT NULL,
            UNIQUE INDEX UNIQ_23B7F9B9A8F36A1 (id)
        )');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE task');

    }
}
