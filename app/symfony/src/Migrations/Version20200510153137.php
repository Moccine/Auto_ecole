<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200510153137 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, orders_id INT UNSIGNED DEFAULT NULL, shop_id INT DEFAULT NULL, total DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, type TINYINT(1) NOT NULL, INDEX IDX_161498D3A76ED395 (user_id), INDEX IDX_161498D3CFFE9AD6 (orders_id), INDEX IDX_161498D34D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_slot (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, day DATETIME NOT NULL, timeslot LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_1B3294AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE week (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F62F1765E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, hour INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE metting_point (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, activated TINYINT(1) NOT NULL, deactivated_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, address VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, INDEX IDX_B0ECAA2F98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, birth_date DATETIME NOT NULL, zip_code INT NOT NULL, phone VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, registration_number VARCHAR(255) DEFAULT NULL, extra_address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, qualification VARCHAR(255) DEFAULT NULL, birthday DATE DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, photo_size INT DEFAULT NULL, password VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, last_login DATETIME DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, activated TINYINT(1) NOT NULL, deactivated_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_metting_point (user_id INT NOT NULL, metting_point_id INT NOT NULL, INDEX IDX_5E62077FA76ED395 (user_id), INDEX IDX_5E62077FA8043E6D (metting_point_id), PRIMARY KEY(user_id, metting_point_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, hour DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, description LONGTEXT DEFAULT NULL, priority INT DEFAULT NULL, complement_description LONGTEXT DEFAULT NULL, course_number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, orders_id INT UNSIGNED DEFAULT NULL, card_id INT DEFAULT NULL, remote_id INT DEFAULT NULL, payment_intent VARCHAR(100) DEFAULT NULL, payment_intent_id VARCHAR(100) DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, status VARCHAR(100) DEFAULT NULL, currency VARCHAR(3) NOT NULL, stripe_private_key VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_6D28840DCFFE9AD6 (orders_id), UNIQUE INDEX UNIQ_6D28840D4ACC9A20 (card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, instructor_id INT DEFAULT NULL, card_id INT DEFAULT NULL, metting_point_id INT NOT NULL, orders_id INT UNSIGNED DEFAULT NULL, course_date DATETIME NOT NULL, price DOUBLE PRECISION NOT NULL, status VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_169E6FB9CB944F1A (student_id), INDEX IDX_169E6FB98C4FC193 (instructor_id), INDEX IDX_169E6FB94ACC9A20 (card_id), INDEX IDX_169E6FB9A8043E6D (metting_point_id), INDEX IDX_169E6FB9CFFE9AD6 (orders_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_pricing (id INT AUTO_INCREMENT NOT NULL, driving_price DOUBLE PRECISION NOT NULL, code_price DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, activated TINYINT(1) NOT NULL, deactivated_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, address VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, INDEX IDX_5E9E89CB98260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE credit (id INT AUTO_INCREMENT NOT NULL, shop_id INT DEFAULT NULL, card_id INT DEFAULT NULL, total DOUBLE PRECISION NOT NULL, rest DOUBLE PRECISION NOT NULL, course_number INT NOT NULL, rest_course_number INT NOT NULL, INDEX IDX_1CC16EFE4D16C4DD (shop_id), UNIQUE INDEX UNIQ_1CC16EFE4ACC9A20 (card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE france_location (id INT AUTO_INCREMENT NOT NULL, region_code INT NOT NULL, circonscription VARCHAR(255) NOT NULL, chef_lieu VARCHAR(255) NOT NULL, region_name VARCHAR(255) NOT NULL, department_number VARCHAR(255) NOT NULL, department_name VARCHAR(255) NOT NULL, prefecture VARCHAR(255) NOT NULL, commune_name VARCHAR(255) NOT NULL, circonscription_number VARCHAR(255) NOT NULL, posta_code VARCHAR(255) NOT NULL, code_insee VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT UNSIGNED AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, shop_id INT DEFAULT NULL, order_number VARCHAR(50) DEFAULT NULL, status VARCHAR(50) DEFAULT NULL, product_details LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', quantity INT NOT NULL, discount DOUBLE PRECISION NOT NULL, tva DOUBLE PRECISION NOT NULL, bill_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, total DOUBLE PRECISION NOT NULL, currency VARCHAR(255) NOT NULL, INDEX IDX_E52FFDEECB944F1A (student_id), INDEX IDX_E52FFDEE4D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D34D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE time_slot ADD CONSTRAINT FK_1B3294AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE metting_point ADD CONSTRAINT FK_B0ECAA2F98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE user_metting_point ADD CONSTRAINT FK_5E62077FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_metting_point ADD CONSTRAINT FK_5E62077FA8043E6D FOREIGN KEY (metting_point_id) REFERENCES metting_point (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB98C4FC193 FOREIGN KEY (instructor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB94ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9A8043E6D FOREIGN KEY (metting_point_id) REFERENCES metting_point (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFE4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFE4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEECB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D4ACC9A20');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB94ACC9A20');
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFE4ACC9A20');
        $this->addSql('ALTER TABLE metting_point DROP FOREIGN KEY FK_B0ECAA2F98260155');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB98260155');
        $this->addSql('ALTER TABLE user_metting_point DROP FOREIGN KEY FK_5E62077FA8043E6D');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9A8043E6D');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3A76ED395');
        $this->addSql('ALTER TABLE time_slot DROP FOREIGN KEY FK_1B3294AA76ED395');
        $this->addSql('ALTER TABLE user_metting_point DROP FOREIGN KEY FK_5E62077FA76ED395');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9CB944F1A');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB98C4FC193');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEECB944F1A');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D34D16C4DD');
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFE4D16C4DD');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE4D16C4DD');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3CFFE9AD6');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DCFFE9AD6');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9CFFE9AD6');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE time_slot');
        $this->addSql('DROP TABLE week');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE day');
        $this->addSql('DROP TABLE metting_point');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_metting_point');
        $this->addSql('DROP TABLE shop');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_pricing');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE credit');
        $this->addSql('DROP TABLE france_location');
        $this->addSql('DROP TABLE orders');
    }
}
