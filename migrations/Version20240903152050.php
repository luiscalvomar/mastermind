<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903152050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', color_code VARCHAR(20) NOT NULL, status VARCHAR(50) NOT NULL, INDEX IDX_232B318CE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE play (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, received_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', proposed_code VARCHAR(20) NOT NULL, evaluation_result VARCHAR(50) NOT NULL, INDEX IDX_5E89DEBAE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE play ADD CONSTRAINT FK_5E89DEBAE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CE48FD905');
        $this->addSql('ALTER TABLE play DROP FOREIGN KEY FK_5E89DEBAE48FD905');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE play');
    }
}
