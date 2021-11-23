<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211123161712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contain ADD crypto_id INT NOT NULL, ADD portfolio_id INT NOT NULL');
        $this->addSql('ALTER TABLE contain ADD CONSTRAINT FK_4BEFF7C8E9571A63 FOREIGN KEY (crypto_id) REFERENCES crypto (id)');
        $this->addSql('ALTER TABLE contain ADD CONSTRAINT FK_4BEFF7C8B96B5643 FOREIGN KEY (portfolio_id) REFERENCES portfolio (id)');
        $this->addSql('CREATE INDEX IDX_4BEFF7C8E9571A63 ON contain (crypto_id)');
        $this->addSql('CREATE INDEX IDX_4BEFF7C8B96B5643 ON contain (portfolio_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contain DROP FOREIGN KEY FK_4BEFF7C8E9571A63');
        $this->addSql('ALTER TABLE contain DROP FOREIGN KEY FK_4BEFF7C8B96B5643');
        $this->addSql('DROP INDEX IDX_4BEFF7C8E9571A63 ON contain');
        $this->addSql('DROP INDEX IDX_4BEFF7C8B96B5643 ON contain');
        $this->addSql('ALTER TABLE contain DROP crypto_id, DROP portfolio_id');
    }
}
