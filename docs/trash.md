 options={"default":"0"})
 https://stackoverflow.com/questions/43234289/set-doctrine-entity-boolean-field-to-0-instead-of-null




php -S 0.0.0.0:8080 -t public

bin/c(tab) ...

bin/console router:match /bonjour

bin/console debug:router
bin/console debug:autowiring param

bin/console make:controller
bin/console make:controller --no-template
bin/console make:entity
bin/console make:migration
bin/console make:form
bin/console make:entity --regenerate "App\Entity\Review"
bin/console make:crud
bin/console make:fixture
bin/console make:user
bin/console make:auth
bin/console make:voter
bin/console make:command
bin/console make:sub
bin/console make:test

bin/console d:m:m
bin/console doctrine:migrations:migrate
bin/console doctrine:schema:validate
bin/console doctrine:fixtures:load
bin/console doctrine:database:create

bin/console security:hash-password

bin/console lexik:jwt:generate-keypair