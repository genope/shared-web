<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220419062941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panierdetails DROP FOREIGN KEY S2');
        $this->addSql('ALTER TABLE panierdetails CHANGE idCommande idCommande INT DEFAULT NULL');
        $this->addSql('DROP INDEX s1 ON panierdetails');
        $this->addSql('CREATE INDEX idProduit ON panierdetails (idProduit)');
        $this->addSql('DROP INDEX s2 ON panierdetails');
        $this->addSql('CREATE INDEX idCommande ON panierdetails (idCommande)');
        $this->addSql('ALTER TABLE panierdetails ADD CONSTRAINT S2 FOREIGN KEY (idCommande) REFERENCES panier (id_panier)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY A4');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY A5');
        $this->addSql('ALTER TABLE produit CHANGE region region VARCHAR(30) DEFAULT NULL, CHANGE nomCategorie nomCategorie VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX a5 ON produit');
        $this->addSql('CREATE INDEX nomCategorie ON produit (nomCategorie)');
        $this->addSql('DROP INDEX a4 ON produit');
        $this->addSql('CREATE INDEX c2 ON produit (region)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT A4 FOREIGN KEY (region) REFERENCES region (nom)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT A5 FOREIGN KEY (nomCategorie) REFERENCES categorieproduit (nomCategorie)');
        $this->addSql('CREATE UNIQUE INDEX id ON region (id)');
        $this->addSql('CREATE UNIQUE INDEX nom ON region (nom)');
        $this->addSql('ALTER TABLE user ADD locale VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panierdetails DROP FOREIGN KEY FK_3EEC0E173D498C26');
        $this->addSql('ALTER TABLE panierdetails CHANGE idCommande idCommande INT NOT NULL');
        $this->addSql('DROP INDEX idcommande ON panierdetails');
        $this->addSql('CREATE INDEX S2 ON panierdetails (idCommande)');
        $this->addSql('DROP INDEX idproduit ON panierdetails');
        $this->addSql('CREATE INDEX S1 ON panierdetails (idProduit)');
        $this->addSql('ALTER TABLE panierdetails ADD CONSTRAINT FK_3EEC0E173D498C26 FOREIGN KEY (idCommande) REFERENCES panier (id_panier)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27F62F176');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC277006D47E');
        $this->addSql('ALTER TABLE produit CHANGE region region VARCHAR(20) DEFAULT NULL, CHANGE nomCategorie nomCategorie VARCHAR(20) DEFAULT NULL');
        $this->addSql('DROP INDEX c2 ON produit');
        $this->addSql('CREATE INDEX A4 ON produit (region)');
        $this->addSql('DROP INDEX nomcategorie ON produit');
        $this->addSql('CREATE INDEX A5 ON produit (nomCategorie)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27F62F176 FOREIGN KEY (region) REFERENCES region (nom)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC277006D47E FOREIGN KEY (nomCategorie) REFERENCES categorieproduit (nomCategorie)');
        $this->addSql('DROP INDEX id ON region');
        $this->addSql('DROP INDEX nom ON region');
        $this->addSql('ALTER TABLE user DROP locale');
    }
}
