# KATA TEST CARREFOUR

## Features
- Product listing from FakeStoreAPI
- Shopping cart functionality
- Persistent cart using MySQL
- Vue.js frontend with Pinia state management
- Symfony backend with MySQL

## Setup

1. Clone the repository
2. Run `docker-compose up -d --build`
3. Initialize the database:
   ```bash
   docker-compose exec backend php bin/console d:s:u --force