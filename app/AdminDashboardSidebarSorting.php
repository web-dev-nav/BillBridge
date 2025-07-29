<?php

namespace App;

enum AdminDashboardSidebarSorting: int
{
    case ADMINS = 1;
    case CLIENTS = 2;
    case CATEGORIES = 3;
    case TAXES = 4;
    case PRODUCTS = 5;
    case QUOTES = 6;
    case INVOICES = 7;
    case PAYMENT_QR_CODES = 8;
    case TRANSACTIONS = 9;
    case PAYMENTS = 10;
    case INVOICE_TEMPLATES = 11;
    case SETTINGS = 12;
    case COUNTRIES = 13;
}
