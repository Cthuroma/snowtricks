# snowtricks [![Codacy Badge](https://api.codacy.com/project/badge/Grade/512171a7ce2b48318750ec47834cc762)](https://app.codacy.com/gh/Cthuroma/snowtricks?utm_source=github.com&utm_medium=referral&utm_content=Cthuroma/snowtricks&utm_campaign=Badge_Grade_Settings)


## Dependencies

Dependency  | Version
------------- | -------------
PHP  | 7.3
Composer  | 1.8
Nodejs | 14.15
NPM | 6.14


## Getting started

First clone the repository in your web-server directory
```git
git clone https://github.com/Cthuroma/snowtricks.git
```

Install php dependencies using composer.
```bash
composer install
```

Install javascript dependencies using npm
```bash
npm install
```

And then create a ".env.local" using the example ".env" file and override both DATABASE_URL and MAILER_DSN variables.


## Setting the data up

You can use the migrations to set the database up.
```bash
bin/console doctrine:migrations:migrate
```

After that use the fixtures to get a default set of data.
```bash
bin/console doctrine:fixtures:load
```

## Building the Front

Build the js and css using this command.
```bash
encore dev
```


## Testing

You can execute the tests by using the following command.
```bash
bin/phpunit
```

Only smoke tests of various URLs are implemented.
