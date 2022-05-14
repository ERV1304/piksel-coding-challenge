<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220514090537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rightsowner (id INT AUTO_INCREMENT NOT NULL, episode_id INT NOT NULL, studio_id INT NOT NULL, UNIQUE INDEX UNIQ_47E4B831362B62A0 (episode_id), INDEX IDX_47E4B831446F285F (studio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE royalty (id INT AUTO_INCREMENT NOT NULL, studio_id INT NOT NULL, payment DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_4B26F605446F285F (studio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE studio (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE viewing (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, episode_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_F5BB46989395C3F3 (customer_id), INDEX IDX_F5BB4698362B62A0 (episode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rightsowner ADD CONSTRAINT FK_47E4B831362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id)');
        $this->addSql('ALTER TABLE rightsowner ADD CONSTRAINT FK_47E4B831446F285F FOREIGN KEY (studio_id) REFERENCES studio (id)');
        $this->addSql('ALTER TABLE royalty ADD CONSTRAINT FK_4B26F605446F285F FOREIGN KEY (studio_id) REFERENCES studio (id)');
        $this->addSql('ALTER TABLE viewing ADD CONSTRAINT FK_F5BB46989395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE viewing ADD CONSTRAINT FK_F5BB4698362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE viewing DROP FOREIGN KEY FK_F5BB46989395C3F3');
        $this->addSql('ALTER TABLE rightsowner DROP FOREIGN KEY FK_47E4B831362B62A0');
        $this->addSql('ALTER TABLE viewing DROP FOREIGN KEY FK_F5BB4698362B62A0');
        $this->addSql('ALTER TABLE rightsowner DROP FOREIGN KEY FK_47E4B831446F285F');
        $this->addSql('ALTER TABLE royalty DROP FOREIGN KEY FK_4B26F605446F285F');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE rightsowner');
        $this->addSql('DROP TABLE royalty');
        $this->addSql('DROP TABLE studio');
        $this->addSql('DROP TABLE viewing');
    }
}
