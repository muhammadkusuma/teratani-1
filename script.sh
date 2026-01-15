#!/bin/bash

# Cek parameter path
if [ -z "$1" ]; then
  echo "Usage: ./script.sh /var/www/html/sistem-toko-tani"
  exit 1
fi

PROJECT_PATH=$1

echo "Masuk ke directory project..."
cd $PROJECT_PATH || exit 1

echo "=============================="
echo "Git reset --hard"
echo "=============================="
git reset --hard

echo "=============================="
echo "Git pull"
echo "=============================="
git pull

echo "=============================="
echo "Set permission Laravel"
echo "=============================="

# Ownership (default Ubuntu + Nginx/Apache)
sudo chown -R www-data:www-data $PROJECT_PATH

# Permission dasar
sudo find $PROJECT_PATH -type f -exec chmod 644 {} \;
sudo find $PROJECT_PATH -type d -exec chmod 755 {} \;

# Permission wajib Laravel
sudo chmod -R 775 $PROJECT_PATH/storage
sudo chmod -R 775 $PROJECT_PATH/bootstrap/cache

echo "=============================="
echo "DONE 🚀 Laravel updated & permission fixed"
echo "=============================="
