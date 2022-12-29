# projet-11-o-troc-back

#### This project runs on Symfony 5.4 and php 7
To deploy it on an apache server, make sure  you're in your server's terminal in your /var/www/html folder, then :
- run the `git clone git@github.com:O-clock-Inca/projet-11-o-troc-back.git` command.
- run `composer install` to upload all the necessary depedencies
- run `nano .env.local`  and edit it with your own paremeters according to the .env file  example (make sure your user in the `DATABASE_URL` as rights to create a database in youy database manager)
- press `ctrl+x` to exit , `yes` to save and `entr` to validate your `nano .env.local` file
- run `php bin/console doctrine:database:create` to create your database
- run `php bin/console doctrine:migrations:migrate` to create your tables in your database according to the last migrations in the project
- run `php bin/console doctrine:fixtures:load` to fill your database with MainCategories, Categories, and a User ADMIN
- run `php bin/console lexik:jwt:generate-keypair` to generate the lexik's authentication key for the api part
- run `nano .env.local` and change the `APP_ENV=dev` into `APP_ENV=prod`

#### You can now access it from your server's url followed by `projet-11-o-troc-back/public/login` and log you in with `otroc5@oclock.io` as user and `otroc` as password