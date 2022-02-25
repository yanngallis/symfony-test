# Symfony Test Application

## Clone repository
```
git clone git@github.com:yanngallis/symfony-test.git
```

## Launch docker containers
```
docker-compose up -d
```

## Install dependencies
```
composer install
```

## Install JS dependencies
```
npm install
```

## Build assets
```
npm run build
```

## Launch migrations
```
php bin/console d:m:m
```

## Launch fixtures
```
php bin/console doctrine:fixtures:load
```

## Launch server
```
symfony serve -d
```