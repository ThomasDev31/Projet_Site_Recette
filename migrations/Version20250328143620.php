<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328143620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD recette_id INT DEFAULT NULL, CHANGE content commentaires LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307F89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307F89312FE9 ON message (recette_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390537A1329
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_49BB6390537A1329 ON recette
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recette DROP message_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F89312FE9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B6BD307F89312FE9 ON message
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP recette_id, CHANGE commentaires content LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recette ADD message_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recette ADD CONSTRAINT FK_49BB6390537A1329 FOREIGN KEY (message_id) REFERENCES message (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_49BB6390537A1329 ON recette (message_id)
        SQL);
    }
}
