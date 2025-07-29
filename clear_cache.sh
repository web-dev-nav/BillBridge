#!/bin/bash

echo "🧹 Clearing Laravel caches..."

# Clear application cache
php artisan cache:clear
echo "✅ Application cache cleared"

# Clear configuration cache
php artisan config:clear
echo "✅ Configuration cache cleared"

# Clear compiled views cache
php artisan view:clear
echo "✅ View cache cleared"

# Clear route cache
php artisan route:clear
echo "✅ Route cache cleared"

# Clear compiled class files
php artisan clear-compiled
echo "✅ Compiled files cleared"

# Optimize for production (optional)
# php artisan optimize
# echo "✅ Application optimized"

echo ""
echo "🎉 All caches cleared successfully!"
echo "💡 You can now access your updated BillBridge panels:"
echo "   Admin Panel: http://127.0.0.1:8000/"
echo "   Client Panel: http://127.0.0.1:8000/client"
echo ""
echo "🔑 Default login:"
echo "   Email: admin@billbridge.com"
echo "   Password: 123456"