<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200430203204 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE supplier_proposal ADD customer_request_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE supplier_proposal ADD CONSTRAINT FK_326CFCF3BFB7BC27 FOREIGN KEY (customer_request_id) REFERENCES customer_request (id)');
        $this->addSql('CREATE INDEX IDX_326CFCF3BFB7BC27 ON supplier_proposal (customer_request_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE supplier_proposal DROP FOREIGN KEY FK_326CFCF3BFB7BC27');
        $this->addSql('DROP INDEX IDX_326CFCF3BFB7BC27 ON supplier_proposal');
        $this->addSql('ALTER TABLE supplier_proposal DROP customer_request_id');
    }
}
