<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200929150619 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cpu (id INT AUTO_INCREMENT NOT NULL, manufacturer VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, cores INT NOT NULL, threads INT NOT NULL, base_clock DOUBLE PRECISION NOT NULL, boost_clock DOUBLE PRECISION NOT NULL, release_year INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE node (id INT AUTO_INCREMENT NOT NULL, result_id INT NOT NULL, clock DOUBLE PRECISION NOT NULL, voltage DOUBLE PRECISION NOT NULL, proof VARCHAR(255) DEFAULT NULL, verified TINYINT(1) NOT NULL, INDEX IDX_857FE8457A7B643 (result_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, cpu_id INT NOT NULL, max_speed DOUBLE PRECISION NOT NULL, max_speed_voltage DOUBLE PRECISION NOT NULL, INDEX IDX_136AC113A76ED395 (user_id), INDEX IDX_136AC1133917014 (cpu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE node ADD CONSTRAINT FK_857FE8457A7B643 FOREIGN KEY (result_id) REFERENCES result (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1133917014 FOREIGN KEY (cpu_id) REFERENCES cpu (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1133917014');
        $this->addSql('ALTER TABLE node DROP FOREIGN KEY FK_857FE8457A7B643');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113A76ED395');
        $this->addSql('DROP TABLE cpu');
        $this->addSql('DROP TABLE node');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE user');
    }
}
