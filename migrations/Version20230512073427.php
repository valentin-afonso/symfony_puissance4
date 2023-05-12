<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230512073427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_controller (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, player1_id INTEGER DEFAULT NULL, player2_id INTEGER DEFAULT NULL, board VARCHAR(255) NOT NULL, winner INTEGER DEFAULT NULL, current_turn INTEGER NOT NULL, CONSTRAINT FK_A22EA177C0990423 FOREIGN KEY (player1_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A22EA177D22CABCD FOREIGN KEY (player2_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A22EA177C0990423 ON game_controller (player1_id)');
        $this->addSql('CREATE INDEX IDX_A22EA177D22CABCD ON game_controller (player2_id)');
        $this->addSql('CREATE TABLE move (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER DEFAULT NULL, row_name VARCHAR(255) NOT NULL, column_name VARCHAR(255) NOT NULL, player INTEGER NOT NULL, CONSTRAINT FK_EF3E3778E48FD905 FOREIGN KEY (game_id) REFERENCES game_controller (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EF3E3778E48FD905 ON move (game_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE game_controller');
        $this->addSql('DROP TABLE move');
    }
}
