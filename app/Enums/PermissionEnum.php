<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case USER_CREATE = 'user.create';
    case USER_READ = 'user.read';
    case USER_UPDATE = 'user.update';
    case USER_DELETE = 'user.delete';

    case PRODUCT_CREATE = 'product.create';
    case PRODUCT_READ = 'product.read';
    case PRODUCT_UPDATE = 'product.update';
    case PRODUCT_DELETE = 'product.delete';
}
