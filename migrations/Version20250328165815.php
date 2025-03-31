<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328165815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Création de la table user
        $this->addSql('CREATE TABLE "user" (
            id SERIAL NOT NULL, 
            email VARCHAR(180) NOT NULL, 
            roles JSON NOT NULL, 
            password VARCHAR(255) NOT NULL, 
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        
        // Ajout de la colonne city
        $this->addSql('ALTER TABLE "user" ADD city VARCHAR(100) DEFAULT NULL');
    }
}
