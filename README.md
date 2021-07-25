[![Codacy Badge](https://app.codacy.com/project/badge/Grade/455e24871f67401aaa4237479cd48af5)](https://www.codacy.com/gh/Benitorax/ocproject6/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Benitorax/ocproject6&amp;utm_campaign=Badge_Grade)

# Project as part of OpenClassrooms training

The project is developed with Symfony, its components and bundles. The only allowed third-party librairies are for test, fixtures and quality code.

It is a community website about snowboard tricks where users can:

-   Sign up and sign in.
-   Reset password.
-   Add, edit and delete trick.
-   Publish comments for each trick page.
-   Add avatar to be display next to theirs comments.

Only logged users can submit trick and comment.

## Getting started
### Step 1: Configure environment variables
Copy the `.env file` in project directory, rename it to `.env.local` and configure the following variables for:
  - the database:
  ```false
  DATABASE_URL=
  ```

  - and the emailing:
  ```false
  MAILER_DSN=
  ```

### Step 2: Install components and librairies
Run the following command:
```false
composer install
```

### Step 3: Create database and tables
Run the following command:
```false
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
```

### Step 4: Create fixtures
Run the following command:
```false
php bin/console doctrine:fixtures:load
```

### Step 5: Launch the server
Run the following command:
```false
php -S 127.0.0.1:8000 -t public
```

Or with Symfony CLI:
```false
symfony serve -d
```

## Third-party librairies
-   [Ramsey/Uuid](https://github.com/ramsey/uuid) for uuid inside model classes. 
-   [Twig](https://github.com/twigphp/Twig) for the template engine.
-   [FakerPHP](https://github.com/fakerphp/faker) to load fixtures.
-   [PHPUnit](https://github.com/sebastianbergmann/phpunit) to run tests.

## Clean code
-   [PHPStan](https://github.com/phpstan/phpstan): level 8
-   [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer): PSR1 and PSR12
