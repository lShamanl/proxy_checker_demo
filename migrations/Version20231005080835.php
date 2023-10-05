<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231005080835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE auth_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE proxy_checker_check_list_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE proxy_checker_proxy_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE auth_users (id BIGINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, email VARCHAR(255) NOT NULL, user_roles JSON DEFAULT \'[]\' NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8A1F49CE7927C74 ON auth_users (email)');
        $this->addSql('COMMENT ON COLUMN auth_users.id IS \'(DC2Type:auth_user_id)\'');
        $this->addSql('COMMENT ON COLUMN auth_users.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN auth_users.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE proxy_checker_check_lists (id BIGINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, payload VARCHAR(255) NOT NULL, all_iteration VARCHAR(255) DEFAULT NULL, success_iteration VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN proxy_checker_check_lists.id IS \'(DC2Type:proxy_checker_check_list_id)\'');
        $this->addSql('COMMENT ON COLUMN proxy_checker_check_lists.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN proxy_checker_check_lists.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN proxy_checker_check_lists.end_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE proxy_checker_proxies (id BIGINT NOT NULL, proxy_id BIGINT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ip_proxy VARCHAR(255) NOT NULL, ip_real VARCHAR(255) NOT NULL, port VARCHAR(255) NOT NULL, proxy_type proxy_checker_proxy_type NOT NULL, proxy_status proxy_checker_proxy_status NOT NULL, country VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, timeout VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E60AA738DB26A4E ON proxy_checker_proxies (proxy_id)');
        $this->addSql('COMMENT ON COLUMN proxy_checker_proxies.id IS \'(DC2Type:proxy_checker_proxy_id)\'');
        $this->addSql('COMMENT ON COLUMN proxy_checker_proxies.proxy_id IS \'(DC2Type:proxy_checker_check_list_id)\'');
        $this->addSql('COMMENT ON COLUMN proxy_checker_proxies.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN proxy_checker_proxies.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN proxy_checker_proxies.proxy_type IS \'(DC2Type:proxy_checker_proxy_type)\'');
        $this->addSql('COMMENT ON COLUMN proxy_checker_proxies.proxy_status IS \'(DC2Type:proxy_checker_proxy_status)\'');
        $this->addSql('ALTER TABLE proxy_checker_proxies ADD CONSTRAINT FK_E60AA738DB26A4E FOREIGN KEY (proxy_id) REFERENCES proxy_checker_check_lists (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE auth_user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE proxy_checker_check_list_seq CASCADE');
        $this->addSql('DROP SEQUENCE proxy_checker_proxy_seq CASCADE');
        $this->addSql('ALTER TABLE proxy_checker_proxies DROP CONSTRAINT FK_E60AA738DB26A4E');
        $this->addSql('DROP TABLE auth_users');
        $this->addSql('DROP TABLE proxy_checker_check_lists');
        $this->addSql('DROP TABLE proxy_checker_proxies');
    }
}
