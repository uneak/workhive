<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127165228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE date_schedules (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, date DATE NOT NULL, started_at TIME NOT NULL, ended_at TIME NOT NULL, is_open TINYINT(1) NOT NULL, INDEX IDX_E4DEF3A54177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment_role_rate (id INT AUTO_INCREMENT NOT NULL, equipment_id INT NOT NULL, user_role VARCHAR(255) NOT NULL, hourly_rate NUMERIC(10, 2) NOT NULL, INDEX IDX_B36E0BD4517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipments (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, total_stock INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_equipment (id INT AUTO_INCREMENT NOT NULL, reservation_id INT NOT NULL, equipment_id INT NOT NULL, quantity INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C97FB41CB83297E7 (reservation_id), INDEX IDX_C97FB41C517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_equipment (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, equipment_id INT NOT NULL, quantity INT NOT NULL, assigned_at DATETIME NOT NULL, INDEX IDX_4F9135EA54177093 (room_id), INDEX IDX_4F9135EA517FE9FE (equipment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_role_rate (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, user_role VARCHAR(255) NOT NULL, hourly_rate NUMERIC(10, 2) NOT NULL, INDEX IDX_FDFAADC454177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE week_schedules (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, started_at TIME NOT NULL, ended_at TIME NOT NULL, week_day SMALLINT NOT NULL, INDEX IDX_11EC168E54177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE date_schedules ADD CONSTRAINT FK_E4DEF3A54177093 FOREIGN KEY (room_id) REFERENCES rooms (id)');
        $this->addSql('ALTER TABLE equipment_role_rate ADD CONSTRAINT FK_B36E0BD4517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_equipment ADD CONSTRAINT FK_C97FB41CB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_equipment ADD CONSTRAINT FK_C97FB41C517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_equipment ADD CONSTRAINT FK_4F9135EA54177093 FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_equipment ADD CONSTRAINT FK_4F9135EA517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room_role_rate ADD CONSTRAINT FK_FDFAADC454177093 FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE week_schedules ADD CONSTRAINT FK_11EC168E54177093 FOREIGN KEY (room_id) REFERENCES rooms (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE date_schedules DROP FOREIGN KEY FK_E4DEF3A54177093');
        $this->addSql('ALTER TABLE equipment_role_rate DROP FOREIGN KEY FK_B36E0BD4517FE9FE');
        $this->addSql('ALTER TABLE reservation_equipment DROP FOREIGN KEY FK_C97FB41CB83297E7');
        $this->addSql('ALTER TABLE reservation_equipment DROP FOREIGN KEY FK_C97FB41C517FE9FE');
        $this->addSql('ALTER TABLE room_equipment DROP FOREIGN KEY FK_4F9135EA54177093');
        $this->addSql('ALTER TABLE room_equipment DROP FOREIGN KEY FK_4F9135EA517FE9FE');
        $this->addSql('ALTER TABLE room_role_rate DROP FOREIGN KEY FK_FDFAADC454177093');
        $this->addSql('ALTER TABLE week_schedules DROP FOREIGN KEY FK_11EC168E54177093');
        $this->addSql('DROP TABLE date_schedules');
        $this->addSql('DROP TABLE equipment_role_rate');
        $this->addSql('DROP TABLE equipments');
        $this->addSql('DROP TABLE reservation_equipment');
        $this->addSql('DROP TABLE room_equipment');
        $this->addSql('DROP TABLE room_role_rate');
        $this->addSql('DROP TABLE week_schedules');
    }
}
