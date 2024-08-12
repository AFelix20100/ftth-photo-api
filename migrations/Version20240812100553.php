<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240812100553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE photo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, commande_id INTEGER DEFAULT NULL, intervention_id INTEGER DEFAULT NULL, file_path VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_14B7841882EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_14B784188EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_14B7841882EA2E54 ON photo (commande_id)');
        $this->addSql('CREATE INDEX IDX_14B784188EAE3863 ON photo (intervention_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__commande AS SELECT id, reference_prestation, reference_commande_interne, created_at, updated_at FROM commande');
        $this->addSql('DROP TABLE commande');
        $this->addSql('CREATE TABLE commande (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, reference_prestation VARCHAR(255) NOT NULL, reference_commande_interne VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO commande (id, reference_prestation, reference_commande_interne, created_at, updated_at) SELECT id, reference_prestation, reference_commande_interne, created_at, updated_at FROM __temp__commande');
        $this->addSql('DROP TABLE __temp__commande');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE photo');
        $this->addSql('CREATE TEMPORARY TABLE __temp__commande AS SELECT id, created_at, updated_at, reference_prestation, reference_commande_interne FROM commande');
        $this->addSql('DROP TABLE commande');
        $this->addSql('CREATE TABLE commande (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , reference_prestation VARCHAR(255) DEFAULT NULL, reference_commande_interne VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO commande (id, created_at, updated_at, reference_prestation, reference_commande_interne) SELECT id, created_at, updated_at, reference_prestation, reference_commande_interne FROM __temp__commande');
        $this->addSql('DROP TABLE __temp__commande');
    }
}
