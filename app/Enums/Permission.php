<?php

namespace App\Enums;

enum Permission: string
{
    case ADMIN                  = 'admin';
    case USER_CREATE            = 'user_create';
    case USER_READ              = 'user_read';
    case USER_UPDATE            = 'user_update';
    case USER_DELETE            = 'user_delete';
    case BRAND_CREATE           = 'brand_create';
    case BRAND_READ             = 'brand_read';
    case BRAND_UPDATE           = 'brand_update';
    case BRAND_DELETE           = 'brand_delete';
    case VEHICLE_MODEL_CREATE   = 'vehicle_model_create';
    case VEHICLE_MODEL_READ     = 'vehicle_model_read';
    case VEHICLE_MODEL_UPDATE   = 'vehicle_model_update';
    case VEHICLE_MODEL_DELETE   = 'vehicle_model_delete';
    case VEHICLE_TYPE_CREATE    = 'vehicle_type_create';
    case VEHICLE_TYPE_READ      = 'vehicle_type_read';
    case VEHICLE_TYPE_UPDATE    = 'vehicle_type_update';
    case VEHICLE_TYPE_DELETE    = 'vehicle_type_delete';
    case VEHICLE_CREATE         = 'vehicle_create';
    case VEHICLE_READ           = 'vehicle_read';
    case VEHICLE_UPDATE         = 'vehicle_update';
    case VEHICLE_DELETE         = 'vehicle_delete';
    case VEHICLE_PHOTO_DELETE   = 'vehicle_photo_delete';
    case CITY_CREATE            = 'city_create';
    case CITY_READ              = 'city_read';
    case CITY_UPDATE            = 'city_update';
    case CITY_DELETE            = 'city_delete';
    case CLIENT_CREATE          = 'client_create';
    case CLIENT_READ            = 'client_read';
    case CLIENT_UPDATE          = 'client_update';
    case CLIENT_DELETE          = 'client_delete';
    case CLIENT_PHOTO_DELETE    = 'client_photo_delete';
    case SUPPLIER_CREATE        = 'supplier_create';
    case SUPPLIER_READ          = 'supplier_read';
    case SUPPLIER_UPDATE        = 'supplier_update';
    case SUPPLIER_DELETE        = 'supplier_delete';
    case SUPPLIER_PHOTO_DELETE  = 'supplier_photo_delete';
    case SALE_CREATE            = 'sale_create';
    case SALE_READ              = 'sale_read';
    case SALE_CANCEL            = 'sale_cancel';
    case INSTALLMENT_READ       = 'installment_read';
    case PAYMENT_RECEIVE        = 'payment_receive';
    case PAYMENT_UNDO           = 'payment_undo';
    case COMPANY_UPDATE         = 'company_update';
    case VEHICLE_EXPENSE_CREATE = 'vehicle_expense_create';
    case VEHICLE_EXPENSE_READ   = 'vehicle_expense_read';
    case VEHICLE_EXPENSE_UPDATE = 'vehicle_expense_update';
    case VEHICLE_EXPENSE_DELETE = 'vehicle_expense_delete';
    case EMPLOYEE_CREATE        = 'employee_create';
    case EMPLOYEE_READ          = 'employee_read';
    case EMPLOYEE_UPDATE        = 'employee_update';
    case EMPLOYEE_DELETE        = 'employee_delete';
    case EMPLOYEE_PHOTO_DELETE  = 'employee_photo_delete';
}
