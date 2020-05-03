<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200429175608 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_category (user_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_E6C1FDC1A76ED395 (user_id), INDEX IDX_E6C1FDC112469DE2 (category_id), PRIMARY KEY(user_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier_proposal (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, description LONGTEXT NOT NULL, points INT DEFAULT NULL, accepted TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_326CFCF3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation_criteria (id INT AUTO_INCREMENT NOT NULL, customer_request_id INT NOT NULL, name VARCHAR(255) NOT NULL, weight INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_BB51E2A7BFB7BC27 (customer_request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier_bid (id INT AUTO_INCREMENT NOT NULL, supplier_proposal_id INT NOT NULL, contracts LONGTEXT NOT NULL, accepted TINYINT(1) DEFAULT NULL, price NUMERIC(10, 0) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C502C9062DCE8B49 (supplier_proposal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation_score (id INT AUTO_INCREMENT NOT NULL, evaluation_criteria_id INT DEFAULT NULL, supplier_proposal_id INT NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F76E4AA0CA527E62 (evaluation_criteria_id), INDEX IDX_F76E4AA02DCE8B49 (supplier_proposal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_request (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_274A2B2112469DE2 (category_id), INDEX IDX_274A2B21A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_category ADD CONSTRAINT FK_E6C1FDC1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_category ADD CONSTRAINT FK_E6C1FDC112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_proposal ADD CONSTRAINT FK_326CFCF3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluation_criteria ADD CONSTRAINT FK_BB51E2A7BFB7BC27 FOREIGN KEY (customer_request_id) REFERENCES customer_request (id)');
        $this->addSql('ALTER TABLE supplier_bid ADD CONSTRAINT FK_C502C9062DCE8B49 FOREIGN KEY (supplier_proposal_id) REFERENCES supplier_proposal (id)');
        $this->addSql('ALTER TABLE evaluation_score ADD CONSTRAINT FK_F76E4AA0CA527E62 FOREIGN KEY (evaluation_criteria_id) REFERENCES evaluation_criteria (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE evaluation_score ADD CONSTRAINT FK_F76E4AA02DCE8B49 FOREIGN KEY (supplier_proposal_id) REFERENCES supplier_proposal (id)');
        $this->addSql('ALTER TABLE customer_request ADD CONSTRAINT FK_274A2B2112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE customer_request ADD CONSTRAINT FK_274A2B21A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_category DROP FOREIGN KEY FK_E6C1FDC1A76ED395');
        $this->addSql('ALTER TABLE supplier_proposal DROP FOREIGN KEY FK_326CFCF3A76ED395');
        $this->addSql('ALTER TABLE customer_request DROP FOREIGN KEY FK_274A2B21A76ED395');
        $this->addSql('ALTER TABLE supplier_bid DROP FOREIGN KEY FK_C502C9062DCE8B49');
        $this->addSql('ALTER TABLE evaluation_score DROP FOREIGN KEY FK_F76E4AA02DCE8B49');
        $this->addSql('ALTER TABLE user_category DROP FOREIGN KEY FK_E6C1FDC112469DE2');
        $this->addSql('ALTER TABLE customer_request DROP FOREIGN KEY FK_274A2B2112469DE2');
        $this->addSql('ALTER TABLE evaluation_score DROP FOREIGN KEY FK_F76E4AA0CA527E62');
        $this->addSql('ALTER TABLE evaluation_criteria DROP FOREIGN KEY FK_BB51E2A7BFB7BC27');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_category');
        $this->addSql('DROP TABLE supplier_proposal');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE evaluation_criteria');
        $this->addSql('DROP TABLE supplier_bid');
        $this->addSql('DROP TABLE evaluation_score');
        $this->addSql('DROP TABLE customer_request');
    }
}
