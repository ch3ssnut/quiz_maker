<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230420074146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE words_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE messenger_messages_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE categories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE questions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quiz_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE categories (id INT NOT NULL, quiz_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3AF34668853CD175 ON categories (quiz_id)');
        $this->addSql('CREATE TABLE questions (id INT NOT NULL, category_id INT NOT NULL, content VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, is_completed BOOLEAN NOT NULL, anwsers JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8ADC54D512469DE2 ON questions (category_id)');
        $this->addSql('CREATE TABLE quiz (id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A412FA927E3C61F9 ON quiz (owner_id)');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D512469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA927E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE words');
        $this->addSql('DROP INDEX uniq_8d93d649e7927c74');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN email TO username');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE categories_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE questions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quiz_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE words_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE messenger_messages_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_75ea56e016ba31db ON messenger_messages (delivered_at)');
        $this->addSql('CREATE INDEX idx_75ea56e0e3bd61ce ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX idx_75ea56e0fb7336f0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE TABLE words (id INT NOT NULL, name VARCHAR(5) NOT NULL, was_used BOOLEAN DEFAULT NULL, current_word BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE categories DROP CONSTRAINT FK_3AF34668853CD175');
        $this->addSql('ALTER TABLE questions DROP CONSTRAINT FK_8ADC54D512469DE2');
        $this->addSql('ALTER TABLE quiz DROP CONSTRAINT FK_A412FA927E3C61F9');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN username TO email');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" (email)');
    }
}
