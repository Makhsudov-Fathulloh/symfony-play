# symfony6-docker
symfony-6 docker

### Docker
To start docker containers:

```
make start
```

To destroy containers:
```
make rm
```

### Composer
To install packages:

```
make composer-install
```

### Database migrations
To run migration

```
make migration-up
```

Rollback the last migration

```
make migration-down
```

Generate new migration file

```
make migration-generate
```

Create test db to run functional tests

```
make create-test-database
```

### PHP Static fixer
```
make php-cs-fix
```

### Functional test
```
make test-functional
```

### Problem 1-2-3-4-5
Problem 1-2-3-4-5
- symfony/framework-bundle is locked to version v6.2.3 and an update of this package was not requested.
- symfony/framework-bundle v6.2.3 requires ext-xml * -> it is missing from your system. Install or enable PHP's xml extension.
```
sudo apt install php-xml
```

