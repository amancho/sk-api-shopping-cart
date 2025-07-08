# sk-api-checkout
API to manage shopping cart actions and checkout process

## How it works
1. **Incoming Request**:
    - The application receives HTTP requests via defined Symfony controllers.

2. **Handling Business Logic**:
    - Controllers delegate the request to handlers (often implemented with CQRS commands or queries) to process the logic.
    - Handlers interact with Repositories to manipulate or retrieve data.

3. **Response Generation**:
    - Results are returned as DTOs (Data Transfer Objects) to ensure a consistent structure for JSON responses.
    - Exceptions (e.g., ) are appropriately handled to return meaningful HTTP status codes.


## Technologies
- PHP 8.2
- Symfony 7.3
- MySQL 8.0
- Nginx
- php-fpm
- phpstan
- phunit
- Docker
- Docker compose

## Architecture and Design Patterns

- Hexagonal architecture with layer separation (Application, Domain, Infrastructure)
- Bounded contexts
- SOLID principles
- CQRS
- DDD
- Clean code
- Events

## Basic Folder Structure
```
.
├── bin
├── docker
│   ├── images
│   │   ├── mysql
│   │   ├── nginx
│   │   └── php
├── migrations
├── public
│   ├── blank.html
│   └── index.php
├── src
│   ├── Application
│   │   ├── Cart
│   │   │   ├── Command
│   │   │   └── Query
|   │   ├── Order
│   │   └── Product
│   ├── Domain
│   │   ├── Cart
│   │   │   ├── Entity
│   │   │   ├── Events
│   │   │   ├── Exception
│   │   │   ├── Repository
│   │   │   └── ValueObject
|   │   ├── Order
│   │   ├── Product
|   │   └── Shared
│   ├── Infrastructure
│   │   ├── Cart
│   │   │   ├── Persitence
│   │   │   └── Symfony
|   │   ├── Order
│   │   ├── Product
|   │   └── Shared
│   │   │   └── Persistence
│   └── Kernel.php
├── docker-compose.yml
├── Makefile
├── README.md
└── symfony.lock
```

## Explanation of use Auto-increment IDs and Public UUIDs
- Use **Auto-increment IDs** internally in the database for efficiency and relation.
- Use **Public UUID** in API endpoints when sharing with users or other systems for security.


## Project set up

- Follow this steps to create a local database with dummy data set.
- Please wait a couple of seconds after running each command so the services can start properly.
- Check cart.public_id to add, modify and delete products

### Build and start
```
git clone git@github.com:amancho/sk-api-shopping-cart.git
cd sk-api-shopping-cart
```
```
make build
```
```
make prepare
```
```
make init-db
```

### Run the application.

```
make up
```

### Open API Doc
Functional open API tool
http://172.33.0.4:8080/api-doc.html

## Example Usage
- Create a cart (POST). Copy the UUID from the response for later use:
```
http://172.33.0.4:8080/carts
```
- Add item to cart (POST)
```
http://172.33.0.4:8080/carts/31bf234f-984a-4df8-8f03-e0ea81bddc23/item
```
- Udpate item cart (PUT)
```
http://172.33.0.4:8080/carts/31bf234f-984a-4df8-8f03-e0ea81bddc23/item/62bf234f-984a-4df8-8f03-e0ea81bddc46
```
- Remove item from cart (DELETE)
```
http://172.33.0.4:8080/carts/31bf234f-984a-4df8-8f03-e0ea81bddc23/item/62bf234f-984a-4df8-8f03-e0ea81bddc46
```

## Execute phpstan to static analysis

```
make phpstan
```

### Execute tests

```
make test
```
