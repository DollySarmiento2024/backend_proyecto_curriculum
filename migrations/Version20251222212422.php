<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251222212422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE empresa ADD account_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE empresa ADD CONSTRAINT FK_B8D75A509B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B8D75A509B6B5FBA ON empresa (account_id)');
        $this->addSql('ALTER TABLE usuario ADD account_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05D9B6B5FBA ON usuario (account_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empresa DROP FOREIGN KEY FK_B8D75A509B6B5FBA');
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05D9B6B5FBA');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP INDEX UNIQ_B8D75A509B6B5FBA ON empresa');
        $this->addSql('ALTER TABLE empresa DROP account_id');
        $this->addSql('DROP INDEX UNIQ_2265B05D9B6B5FBA ON usuario');
        $this->addSql('ALTER TABLE usuario DROP account_id');
    }
}
