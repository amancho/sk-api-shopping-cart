<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250706202124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carts (id INT AUTO_INCREMENT NOT NULL, public_id VARCHAR(36) NOT NULL, code VARCHAR(30) NOT NULL, checkout_id VARCHAR(255) DEFAULT NULL, user_id INT DEFAULT NULL, order_id INT DEFAULT NULL, status VARCHAR(50) NOT NULL, session_id VARCHAR(255) DEFAULT NULL, shipping_name VARCHAR(150) DEFAULT NULL, shipping_address VARCHAR(255) DEFAULT NULL, shipping_city VARCHAR(100) DEFAULT NULL, shipping_postal_code VARCHAR(25) DEFAULT NULL, shipping_province VARCHAR(50) DEFAULT NULL, shipping_country VARCHAR(50) DEFAULT NULL, shipping_phone VARCHAR(50) DEFAULT NULL, shipping_email VARCHAR(100) DEFAULT NULL, metadata JSON DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE carts');
    }
}
