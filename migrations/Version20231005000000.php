<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231005000000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf(!$this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform, 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql("CREATE TYPE proxy_checker_proxy_type as ENUM('http', 'socks', 'other')");
        $this->addSql("CREATE TYPE proxy_checker_proxy_status as ENUM('work', 'not_work')");
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(!$this->connection->getDatabasePlatform() instanceof PostgreSQLPlatform, 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('DROP TYPE IF EXISTS proxy_checker_proxy_type');
        $this->addSql('DROP TYPE IF EXISTS proxy_checker_proxy_status');
    }
}
