<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120702191259 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE status (statusId INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(statusId)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA339393B26");
        $this->addSql("DROP INDEX IDX_97A0ADA339393B26 ON ticket");
        $this->addSql("ALTER TABLE ticket CHANGE boardid statusId INT DEFAULT NULL");
        $this->addSql("ALTER TABLE ticket ADD storyId INT DEFAULT NULL");
        $this->addSql("ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA33A4FD046 FOREIGN KEY (storyId) REFERENCES story(storyId)");
        $this->addSql("ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3F112F078 FOREIGN KEY (statusId) REFERENCES status(statusId)");
        $this->addSql("CREATE INDEX IDX_97A0ADA33A4FD046 ON ticket (storyId)");
        $this->addSql("CREATE INDEX IDX_97A0ADA3F112F078 ON ticket (statusId)");
        $this->addSql("ALTER TABLE story ADD sort INT NOT NULL");
        $this->addSql("ALTER TABLE story DROP x");
        $this->addSql("ALTER TABLE story DROP y");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3F112F078");
        $this->addSql("DROP TABLE status");
        $this->addSql("ALTER TABLE story CHANGE sort y INT NOT NULL");
        $this->addSql("ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA33A4FD046");
        $this->addSql("ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3F112F078");
        $this->addSql("DROP INDEX IDX_97A0ADA33A4FD046 ON ticket");
        $this->addSql("DROP INDEX IDX_97A0ADA3F112F078 ON ticket");
        $this->addSql("ALTER TABLE ticket ADD boardId INT DEFAULT NULL, DROP storyId, DROP statusId");
        $this->addSql("ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA339393B26 FOREIGN KEY (boardId) REFERENCES board(boardId)");
        $this->addSql("CREATE INDEX IDX_97A0ADA339393B26 ON ticket (boardId)");
    }
}