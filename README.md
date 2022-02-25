# Symfony Test Application

## Requirements
- PHP 7.3
- Docker & Docker compose
- Symfony CLI
- Node & NPM

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
symfony d:m:m
```

## Launch fixtures
```
symfony doctrine:fixtures:load
```

## Launch server
```
symfony serve -d
```