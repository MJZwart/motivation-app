#!/usr/bin/env sh

# abort on errors
# set -e

   echo "Running in $APP_ENV"
# build
npm ci
echo "Building"
npm run build
echo "Successfully built"

# echo "Performing php artisan migrate:fresh --force --seed"
# php artisan migrate --force --seed

echo "Clear application cache"
php artisan cache:clear

echo "Migrating"
php artisan migrate --force

# echo "Generating key"
# php artisan key:generate

# navigate into the build output directory
# cd dist

# if you are deploying to a custom domain
# echo 'www.example.com' > CNAME

# git init
# git add -A
# git commit -m 'deploy'

# if you are deploying to https://<USERNAME>.github.io
# git push -f git@github.com:MJZwart/MJZwart.github.io.git main

# if you are deploying to https://<USERNAME>.github.io/<REPO>
# git push -f git@github.com:MJZwart/MJZwart.git main:gh-pages

# cd -