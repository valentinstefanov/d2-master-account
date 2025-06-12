<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250612173645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE BNET (uid INT AUTO_INCREMENT NOT NULL, acct_username VARCHAR(32) DEFAULT NULL, username VARCHAR(32) DEFAULT NULL, acct_userid INT DEFAULT NULL, acct_passhash1 VARCHAR(128) DEFAULT NULL, acct_email VARCHAR(128) DEFAULT NULL, auth_admin VARCHAR(16) DEFAULT 'false' NOT NULL, auth_normallogin VARCHAR(16) DEFAULT 'true' NOT NULL, auth_changepass VARCHAR(16) DEFAULT 'true' NOT NULL, auth_changeprofile VARCHAR(16) DEFAULT 'true' NOT NULL, auth_botlogin VARCHAR(16) DEFAULT 'true' NOT NULL, auth_operator VARCHAR(16) DEFAULT 'false' NOT NULL, new_at_team_flag INT DEFAULT 0 NOT NULL, auth_lockk VARCHAR(16) DEFAULT '0' NOT NULL, auth_command_groups VARCHAR(128) DEFAULT '1' NOT NULL, acct_lastlogin_time INT DEFAULT 0 NOT NULL, acct_lastlogin_owner VARCHAR(128) DEFAULT NULL, acct_lastlogin_clienttag VARCHAR(128) DEFAULT NULL, acct_lastlogin_ip VARCHAR(32) DEFAULT NULL, acct_ctime VARCHAR(128) DEFAULT NULL, auth_voice_diablo_ii1 VARCHAR(128) DEFAULT NULL, auth_operator_diablo_ii1 VARCHAR(128) DEFAULT NULL, auth_admin_diablo_ii1 VARCHAR(128) DEFAULT NULL, auth_admin_blades VARCHAR(128) DEFAULT NULL, auth_operator_blades VARCHAR(128) DEFAULT NULL, auth_voice_blades VARCHAR(128) DEFAULT NULL, auth_voice1vs1 VARCHAR(128) DEFAULT NULL, auth_admin_veso VARCHAR(128) DEFAULT NULL, auth_operator_veso VARCHAR(128) DEFAULT NULL, c_password VARCHAR(255) DEFAULT NULL, auth_mute VARCHAR(16) DEFAULT '0' NOT NULL, auth_operator_auratum VARCHAR(128) DEFAULT NULL, auth_lock VARCHAR(6) DEFAULT 'false' NOT NULL, auth_locktime INT DEFAULT 0 NOT NULL, auth_lockreason VARCHAR(128) DEFAULT NULL, auth_mutetime INT DEFAULT 0 NOT NULL, auth_mutereason VARCHAR(128) DEFAULT NULL, PRIMARY KEY(uid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE BNET
        SQL);
    }
}
