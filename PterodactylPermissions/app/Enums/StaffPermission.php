<?php

namespace App\Enums;

enum StaffPermission: string
{
    case VIEW_SERVERS = 'view.servers';
    case MANAGE_SERVERS = 'manage.servers';
    case VIEW_USERS = 'view.users';
    case MANAGE_USERS = 'manage.users';
    case MANAGE_ROLES = 'manage.roles';
    case VIEW_ACTIVITY = 'view.activity';
    case MANAGE_SETTINGS = 'manage.settings';
}
