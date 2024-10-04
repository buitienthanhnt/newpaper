<?php
namespace App\Models;

interface AdminUserInterface{
    const TABLE_NAME = 'admin_users';

    const ATTR_NAME = 'name';
    const ATTR_EMAIL = 'email';
    const ATTR_PASSWORD = 'password';
    const ATTR_ACTIVE = 'active';
    const ATTR_LOG_DATE = 'log_date';
    const ATTR_LOG_ERROR_NUM = 'log_error_num';
}

