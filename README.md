# Iran Provinces & Cities API

This project is a RESTful API for managing Iran's provinces and cities, built with PHP and MySQL. It is suitable for use in GIS systems, registration forms, and admin panels.

## Technologies
- PHP 8+
- MySQL
- Composer
- JWT (for authentication)

## Features
- Full CRUD operations for provinces and cities
- Data validation and error handling
- JWT-based authentication
- Standard JSON responses

## Quick Start
1. Install dependencies:
   ```bash
   composer install
   ```
2. Configure your database credentials in `App/Config/dbConnection.php`.
3. Import the `App/sql/iran.sql` file into your MySQL database.
4. Run the project on your server (e.g., XAMPP or Apache).

## Full Documentation
For complete usage guide and examples, see [App/Docs/index.html](App/Docs/index.html). 