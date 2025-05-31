<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250531182457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE password_reset_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(128) NOT NULL, requested_at DATETIME NOT NULL, request_ip VARCHAR(45) DEFAULT NULL, reset_at DATETIME DEFAULT NULL, reset_ip VARCHAR(45) DEFAULT NULL, successful TINYINT(1) DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_C5D0A95A5F37A13B (token), INDEX IDX_C5D0A95AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE password_reset_request ADD CONSTRAINT FK_C5D0A95AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE password_reset_request DROP FOREIGN KEY FK_C5D0A95AA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE password_reset_request
        SQL);
    }
}
