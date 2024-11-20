<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241117120214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alert (id INT AUTO_INCREMENT NOT NULL, task_id VARCHAR(36) NOT NULL, message VARCHAR(255) NOT NULL, reason VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_seen_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', resolved TINYINT(1) NOT NULL, INDEX IDX_17FD46C18DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id VARCHAR(36) NOT NULL, status VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', worker VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE worker (id VARCHAR(36) NOT NULL, active TINYINT(1) NOT NULL, access_token VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C18DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alert DROP FOREIGN KEY FK_17FD46C18DB60186');
        $this->addSql('DROP TABLE alert');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE worker');
    }
}
