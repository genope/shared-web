<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418161127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD activation_token VARCHAR(50) DEFAULT NULL, DROP is_verified');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) DEFAULT 0, DROP activation_token, CHANGE Nom Nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE Prenom Prenom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE Email Email VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE Password Password VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE googleId googleId VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE facebookId facebookId VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_general_ci` COMMENT \'(DC2Type:json)\', CHANGE etat etat LONGTEXT NOT NULL COLLATE `utf8mb4_general_ci` COMMENT \'(DC2Type:json)\', CHANGE adress_host adress_host VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE image_cin image_cin VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE image_profile image_profile VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`');
    }
}
