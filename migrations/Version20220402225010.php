<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220402225010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', DROP Role');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD Role VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, DROP roles, CHANGE Nom Nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE Prenom Prenom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE Email Email VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE Password Password VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE etat etat VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE adress_host adress_host VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE image_cin image_cin VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE image_profile image_profile VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
    }
}
