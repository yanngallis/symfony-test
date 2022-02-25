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

## Application must look like this
<img src="https://i.ibb.co/LS5yxSb/Capture-d-e-cran-2022-02-25-a-17-43-29.png" />


## Test instructions
- Clone the repository.
---
- An unauthenticated user is not allowed to purchase a product.
- When a user clicks on the "Buy the product" link, it will open a Stripe window that will allow the user to enter their credit card information in order to make the purchase.
- Confirm purchage to the user when it is validate.
---
- Commit your changes to the repository.

---
- User's credentials are : admin@test.com / password (created by fixtures)

- Stripe credentials are : 
    - Public key : pk_test_51KX3c7Cltqi2ui8526EIBsemulmBY84cysH7uD9zH6Q1J5Ejpw4nOuEz4fbX9hpqRR10fGMksEcc5VVzPQ54pU2i00dt8z6gaW
    - Private key : sk_test_51KX3c7Cltqi2ui856ZLMGZVA4GYfreLadqdihnMa5vDzEVwVnJbVTztmRe48kY6F9jRXo7n6hjksod5E4RZnIxOb00tAu3CPF7

[Stripe documentation](https://stripe.com/docs/checkout/quickstart)