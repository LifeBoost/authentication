<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230303191439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create oauth_access_tokens table and oauth_refresh_tokens table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            create table `oauth_access_tokens`
            (
                `users_id` varchar(36) not null,
                `access_token` text not null,
                `expires_at` datetime not null,
                `ip` varchar(255),
                `user_agent` text,
                `created_at` datetime default NOW() not null,
                `updated_at` datetime null,
                constraint oauth_access_tokens_pk primary key (`access_token`(768)),
                constraint oauth_access_tokens_users_id_fk foreign key (`users_id`) references users (`id`),
                constraint oauth_access_tokens_users_id_unique unique (`users_id`)
            );
        ");

        $this->addSql("
            create table `oauth_refresh_tokens`
            (
                `users_id` varchar(36) not null,
                `refresh_token` text not null,
                `expires_at` datetime not null,
                `ip` varchar(255),
                `user_agent` text,
                `created_at` datetime default NOW() not null,
                `updated_at` datetime null,
                constraint oauth_refresh_tokens_pk primary key (`refresh_token`(768)),
                constraint oauth_refresh_tokens_users_id_fk foreign key (`users_id`) references users (`id`),
                constraint oauth_refresh_tokens_users_id_unique unique (`users_id`)
            );
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE IF EXISTS `oauth_refresh_tokens`");
        $this->addSql("DROP TABLE IF EXISTS `oauth_access_tokens`");
    }
}
