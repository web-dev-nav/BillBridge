# BillBridge Canadian Settings Update

## 🎯 Quick Update Commands

Run ONE of these commands to update your database to Canadian settings:

### Option 1: Using Artisan Command
```bash
php artisan branding:update --force
```

### Option 2: Using PHP Script
```bash
php apply_canadian_settings.php
```

### Option 3: Clear Caches After Update
```bash
php artisan cache:clear
php artisan config:clear  
php artisan view:clear
```

## 🇨🇦 What Gets Updated

- **Country**: India → Canada
- **State**: Gujarat → Ontario  
- **City**: Surat → Toronto
- **Postal Code**: 394101 → M5V 3A8
- **Country Code**: +91 → +1
- **Currency**: Indian Rupee → Canadian Dollar (CAD)
- **Timezone**: Asia/Kolkata → America/Toronto
- **Phone/Fax**: Canadian format
- **Admin Email**: admin@billbridge.com

## 🔑 Login After Update

- **Email**: admin@billbridge.com
- **Password**: 123456
- **Admin Panel**: http://127.0.0.1:8000/admin

That's it! All Indian settings will be replaced with Canadian ones.