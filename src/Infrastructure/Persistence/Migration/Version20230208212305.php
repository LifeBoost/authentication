<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208212305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create users table and users_confirmation table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            create table `users`
            (
                `id`         varchar(36)                                                                   not null,
                `email`      varchar(255)                                                                  not null,
                `password`   varchar(255)                                                                  not null,
                `first_name` varchar(255)                                                                  not null,
                `last_name`  varchar(255)                                                                  not null,
                `status`     enum ('active', 'email_verification', 'disable') default 'email_verification' not null,
                `created_at` datetime                                         default NOW()                not null,
                `updated_at` datetime                                                                      null,
                constraint users_pk primary key (`id`),
                constraint users_email_uindex unique (`email`)
            );
        ");

        $this->addSql('
            create table users_confirmation
            (
                `users_id`           varchar(36)            not null,
                `email`              varchar(255)           not null,
                `confirmation_token` varchar(36)            not null,
                `created_at`         datetime default NOW() not null,
                `updated_at`         datetime               null,
                constraint users_confirmation_unique_confirmation_token unique (`confirmation_token`),
                constraint users_confirmation_unique_email unique (`email`),
                constraint users_confirmation_users_id_fk foreign key (`users_id`) references users (`id`) ON DELETE CASCADE
            );
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS `users`');
        $this->addSql('DROP TABLE IF EXISTS `users_confirmation`');
    }
}
