![Logo](./resources/js/assets/images/logo.png)

# Multi-Tenant Sync

Multi-Tenant Sync is a system designed to efficiently execute queries or functions in Software as a Service (SAAS) applications that employ the separate database per tenant method. This system is built using Laravel 11 on the backend and Vue.js 3 on the frontend, and requires at least PHP 8.3 for optimal operation.

## Installation

To install Multi-Tenant Sync, follow these steps:

1. Clone this repository:

   ```bash
   git clone https://github.com/dazza-dev/multi-tenant-sync.git
   ```

2. Navigate to the project directory:

   ```bash
   cd multi-tenant-sync
   ```

3. Install PHP dependencies with Composer and Sail:

   ```bash
   composer install && ./vendor/bin/sail up -d
   ```

4. Run the database migrations:

   ```bash
   ./vendor/bin/sail artisan migrate
   ```

5. Start the job queue service:

   ```bash
   ./vendor/bin/sail artisan queue:work
   ```

## Usage

The usage of Multi-Tenant Sync is divided into the following steps:

1. **Create a New Project**: Create a new project by clicking the "New Project" button. Fill in the connection details to the main project database.

2. **Add Query**: Once the project is created, add the query that will be used in the main database to retrieve the list of tenants with the connection data of each tenant's database.

3. **Execute Job**: Enter the project you created and execute a job by clicking the "Execute Job" button. This will start the execution of the query on each tenant's database and synchronize the results.

## Requirements

Make sure you have the following installed before starting:

- PHP 8.3
- Composer
- Node.js >= 12
- NPM or Yarn

## Contributions

Contributions are welcome. If you find any bugs or have ideas for improvements, please open an issue or send a pull request. Make sure to follow the contribution guidelines.

## Author

Multi-Tenant Sync was created by [DAZZA](https://github.com/dazza-dev).

## License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).
