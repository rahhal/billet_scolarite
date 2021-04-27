<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419125024 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annee_scolaire (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, annee INT NOT NULL, current TINYINT(1) DEFAULT NULL, directeur VARCHAR(70) DEFAULT NULL, surveillant VARCHAR(70) DEFAULT NULL, INDEX IDX_97150C2BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, niveau_id INT DEFAULT NULL, user_id INT NOT NULL, code VARCHAR(8) DEFAULT NULL, libelle VARCHAR(30) NOT NULL, INDEX IDX_8F87BF96B3E9C81 (niveau_id), INDEX IDX_8F87BF96A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleve (id INT AUTO_INCREMENT NOT NULL, nationalite_id INT DEFAULT NULL, classe_annee_derniere_id INT NOT NULL, user_id INT NOT NULL, etablissement_annee_derniere_id INT DEFAULT NULL, classe_annee_actuelle_id INT DEFAULT NULL, cin VARCHAR(8) DEFAULT NULL, identifiant VARCHAR(8) NOT NULL, nomprenom VARCHAR(60) NOT NULL, sexe INT DEFAULT NULL, date_naissance DATE DEFAULT NULL, lieu_naissance VARCHAR(30) DEFAULT NULL, nbr_frere_secondaire INT DEFAULT NULL, nbr_frere_universitaire INT DEFAULT NULL, est_orphelin TINYINT(1) DEFAULT NULL, orphelin_qui INT DEFAULT NULL, parent_divorce TINYINT(1) DEFAULT NULL, garde INT DEFAULT NULL, nom_pere VARCHAR(60) DEFAULT NULL, metier_pere VARCHAR(30) DEFAULT NULL, cin_pere VARCHAR(8) DEFAULT NULL, date_emession_cin DATE DEFAULT NULL, nom_mere VARCHAR(60) DEFAULT NULL, metier_mere VARCHAR(30) DEFAULT NULL, adresse_domicile VARCHAR(120) DEFAULT NULL, adresse_travail VARCHAR(120) DEFAULT NULL, fixe VARCHAR(8) DEFAULT NULL, gsm VARCHAR(8) DEFAULT NULL, email VARCHAR(30) DEFAULT NULL, procureur VARCHAR(60) DEFAULT NULL, ci_procureur VARCHAR(8) DEFAULT NULL, num_ordre INT DEFAULT NULL, num_ordre_u INT DEFAULT NULL, INDEX IDX_ECA105F71B063272 (nationalite_id), INDEX IDX_ECA105F7FE838FE8 (classe_annee_derniere_id), INDEX IDX_ECA105F7A76ED395 (user_id), INDEX IDX_ECA105F7E6CA67AD (etablissement_annee_derniere_id), INDEX IDX_ECA105F7EF7E1545 (classe_annee_actuelle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enseignant (id INT AUTO_INCREMENT NOT NULL, matiere_id INT DEFAULT NULL, user_id INT NOT NULL, code VARCHAR(50) NOT NULL, nom VARCHAR(100) NOT NULL, INDEX IDX_81A72FA1F46CD258 (matiere_id), INDEX IDX_81A72FA1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etablissement (id INT AUTO_INCREMENT NOT NULL, gouvernorat_id INT NOT NULL, user_id INT NOT NULL, ministere VARCHAR(70) NOT NULL, mandoubia VARCHAR(70) NOT NULL, code VARCHAR(6) NOT NULL, nom VARCHAR(70) NOT NULL, ville VARCHAR(70) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, tel VARCHAR(70) DEFAULT NULL, fax VARCHAR(70) DEFAULT NULL, cle_license LONGTEXT DEFAULT NULL, nbr_attestation_presence LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_20FD592C75B74E2D (gouvernorat_id), INDEX IDX_20FD592CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etablissements (id INT AUTO_INCREMENT NOT NULL, gouvernorat_id INT DEFAULT NULL, code VARCHAR(6) NOT NULL, libelle VARCHAR(70) DEFAULT NULL, INDEX IDX_29F65EB175B74E2D (gouvernorat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gouvernorat (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(2) NOT NULL, libelle VARCHAR(20) DEFAULT NULL, libelle_ar VARCHAR(20) DEFAULT NULL, libelle_fr VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jour_ferie (id INT AUTO_INCREMENT NOT NULL, annee_scolaire_id INT DEFAULT NULL, user_id INT NOT NULL, debut DATE NOT NULL, fin DATE NOT NULL, libelle VARCHAR(30) NOT NULL, INDEX IDX_122AB59331C741 (annee_scolaire_id), INDEX IDX_122AB5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, code VARCHAR(50) NOT NULL, libelle VARCHAR(150) DEFAULT NULL, libelle_fr VARCHAR(150) DEFAULT NULL, INDEX IDX_9014574AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre_conseil (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom_prenom VARCHAR(250) NOT NULL, grade INT NOT NULL, INDEX IDX_8D18043CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nationalite (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(3) NOT NULL, libelle_ar VARCHAR(40) DEFAULT NULL, libelle_fr VARCHAR(40) DEFAULT NULL, libelle_court_ar VARCHAR(50) DEFAULT NULL, code_bac VARCHAR(2) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, section_id INT DEFAULT NULL, user_id INT NOT NULL, code VARCHAR(6) NOT NULL, libelle VARCHAR(30) NOT NULL, niveau_etude VARCHAR(1) NOT NULL, INDEX IDX_4BDFF36BD823E37A (section_id), INDEX IDX_4BDFF36BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE punitions_absences (id INT AUTO_INCREMENT NOT NULL, punitions_absences_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, annee_scolaire_id INT DEFAULT NULL, classe_id INT DEFAULT NULL, eleve_id INT DEFAULT NULL, matiere_id INT DEFAULT NULL, enseignant_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, raison LONGTEXT DEFAULT NULL, objet LONGTEXT DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME DEFAULT NULL, date_impression DATETIME DEFAULT NULL, date_notification1 DATETIME DEFAULT NULL, date_notification2 DATETIME DEFAULT NULL, date_notification3 DATETIME DEFAULT NULL, nbr_heure INT DEFAULT NULL, heure TIME DEFAULT NULL, nbr_jour INT DEFAULT NULL, mode_reglement VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1D50725F77C3E620 (punitions_absences_id), INDEX IDX_1D50725FB03A8386 (created_by_id), INDEX IDX_1D50725F9331C741 (annee_scolaire_id), INDEX IDX_1D50725F8F5EA509 (classe_id), INDEX IDX_1D50725FA6CC7B2 (eleve_id), INDEX IDX_1D50725FF46CD258 (matiere_id), INDEX IDX_1D50725FE455FCC0 (enseignant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE section (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, code VARCHAR(5) NOT NULL, libelle_ar VARCHAR(40) DEFAULT NULL, libelle_fr VARCHAR(40) DEFAULT NULL, INDEX IDX_2D737AEFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sexe (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(1) NOT NULL, code_y VARCHAR(1) DEFAULT NULL, libelle_ar VARCHAR(20) DEFAULT NULL, libelle_fr VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, username VARCHAR(255) DEFAULT NULL, nom_prenom VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annee_scolaire ADD CONSTRAINT FK_97150C2BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF96B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classe ADD CONSTRAINT FK_8F87BF96A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F71B063272 FOREIGN KEY (nationalite_id) REFERENCES nationalite (id)');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F7FE838FE8 FOREIGN KEY (classe_annee_derniere_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F7E6CA67AD FOREIGN KEY (etablissement_annee_derniere_id) REFERENCES etablissements (id)');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F7EF7E1545 FOREIGN KEY (classe_annee_actuelle_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE enseignant ADD CONSTRAINT FK_81A72FA1F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE enseignant ADD CONSTRAINT FK_81A72FA1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592C75B74E2D FOREIGN KEY (gouvernorat_id) REFERENCES gouvernorat (id)');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE etablissements ADD CONSTRAINT FK_29F65EB175B74E2D FOREIGN KEY (gouvernorat_id) REFERENCES gouvernorat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jour_ferie ADD CONSTRAINT FK_122AB59331C741 FOREIGN KEY (annee_scolaire_id) REFERENCES annee_scolaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jour_ferie ADD CONSTRAINT FK_122AB5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE membre_conseil ADD CONSTRAINT FK_8D18043CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE niveau ADD CONSTRAINT FK_4BDFF36BD823E37A FOREIGN KEY (section_id) REFERENCES section (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau ADD CONSTRAINT FK_4BDFF36BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE punitions_absences ADD CONSTRAINT FK_1D50725F77C3E620 FOREIGN KEY (punitions_absences_id) REFERENCES punitions_absences (id)');
        $this->addSql('ALTER TABLE punitions_absences ADD CONSTRAINT FK_1D50725FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE punitions_absences ADD CONSTRAINT FK_1D50725F9331C741 FOREIGN KEY (annee_scolaire_id) REFERENCES annee_scolaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE punitions_absences ADD CONSTRAINT FK_1D50725F8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE punitions_absences ADD CONSTRAINT FK_1D50725FA6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE punitions_absences ADD CONSTRAINT FK_1D50725FF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE punitions_absences ADD CONSTRAINT FK_1D50725FE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jour_ferie DROP FOREIGN KEY FK_122AB59331C741');
        $this->addSql('ALTER TABLE punitions_absences DROP FOREIGN KEY FK_1D50725F9331C741');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F7FE838FE8');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F7EF7E1545');
        $this->addSql('ALTER TABLE punitions_absences DROP FOREIGN KEY FK_1D50725F8F5EA509');
        $this->addSql('ALTER TABLE punitions_absences DROP FOREIGN KEY FK_1D50725FA6CC7B2');
        $this->addSql('ALTER TABLE punitions_absences DROP FOREIGN KEY FK_1D50725FE455FCC0');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F7E6CA67AD');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592C75B74E2D');
        $this->addSql('ALTER TABLE etablissements DROP FOREIGN KEY FK_29F65EB175B74E2D');
        $this->addSql('ALTER TABLE enseignant DROP FOREIGN KEY FK_81A72FA1F46CD258');
        $this->addSql('ALTER TABLE punitions_absences DROP FOREIGN KEY FK_1D50725FF46CD258');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F71B063272');
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF96B3E9C81');
        $this->addSql('ALTER TABLE punitions_absences DROP FOREIGN KEY FK_1D50725F77C3E620');
        $this->addSql('ALTER TABLE niveau DROP FOREIGN KEY FK_4BDFF36BD823E37A');
        $this->addSql('ALTER TABLE annee_scolaire DROP FOREIGN KEY FK_97150C2BA76ED395');
        $this->addSql('ALTER TABLE classe DROP FOREIGN KEY FK_8F87BF96A76ED395');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F7A76ED395');
        $this->addSql('ALTER TABLE enseignant DROP FOREIGN KEY FK_81A72FA1A76ED395');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592CA76ED395');
        $this->addSql('ALTER TABLE jour_ferie DROP FOREIGN KEY FK_122AB5A76ED395');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574AA76ED395');
        $this->addSql('ALTER TABLE membre_conseil DROP FOREIGN KEY FK_8D18043CA76ED395');
        $this->addSql('ALTER TABLE niveau DROP FOREIGN KEY FK_4BDFF36BA76ED395');
        $this->addSql('ALTER TABLE punitions_absences DROP FOREIGN KEY FK_1D50725FB03A8386');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEFA76ED395');
        $this->addSql('DROP TABLE annee_scolaire');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE eleve');
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('DROP TABLE etablissement');
        $this->addSql('DROP TABLE etablissements');
        $this->addSql('DROP TABLE gouvernorat');
        $this->addSql('DROP TABLE jour_ferie');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE membre_conseil');
        $this->addSql('DROP TABLE nationalite');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE punitions_absences');
        $this->addSql('DROP TABLE section');
        $this->addSql('DROP TABLE sexe');
        $this->addSql('DROP TABLE user');
    }
}
