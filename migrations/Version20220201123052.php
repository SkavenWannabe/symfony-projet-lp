<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220201123052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, id_categorie_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, texte VARCHAR(255) NOT NULL, visuel VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_23A0E669F34925F (id_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, id_usager_id INT DEFAULT NULL, date_commande DATE NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_6EEAA67DCF034CDB (id_usager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_commande (id INT AUTO_INCREMENT NOT NULL, id_commande_id INT NOT NULL, id_article_id INT NOT NULL, quantite VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_3170B74B9AF8E3A3 (id_commande_id), INDEX IDX_3170B74BD71E064B (id_article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usager (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, roles VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E669F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DCF034CDB FOREIGN KEY (id_usager_id) REFERENCES usager (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74B9AF8E3A3 FOREIGN KEY (id_commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE ligne_commande ADD CONSTRAINT FK_3170B74BD71E064B FOREIGN KEY (id_article_id) REFERENCES article (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74BD71E064B');
        $this->addSql('ALTER TABLE ligne_commande DROP FOREIGN KEY FK_3170B74B9AF8E3A3');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DCF034CDB');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE ligne_commande');
        $this->addSql('DROP TABLE usager');
    }
}
