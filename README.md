# E-commerce Website (PHP MVC Project)

This is a full-featured e-commerce website built with PHP, following the Model-View-Controller (MVC) architectural pattern. The project is fully containerized using Docker and Docker Compose for easy setup and deployment.

## Features

Based on the project structure, the application supports core e-commerce functionalities:
* User authentication (Login, Register)
* Product catalog display
* Shopping cart functionality
* Order processing
* Admin panel for managing products, orders, and users (inferred from MVC structure)

## Technology Stack

* **Backend:** PHP (Custom MVC framework)
* **Frontend:** PHP-generated views, CSS
* **Database:** MySQL
* **DevOps:** Docker, Docker Compose

## How to Run

This project is configured to run easily using Docker.

### Prerequisites

* [Docker](https://www.docker.com/products/docker-desktop/) installed on your machine.
* [Docker Compose](https://docs.docker.com/compose/install/) (usually included with Docker Desktop).

### Installation & Setup

1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/vietlecd/Ecommerce_Website.git](https://github.com/vietlecd/Ecommerce_Website.git)
    cd Ecommerce_Website
    ```

2.  **Build and Run the Containers:**
    Use Docker Compose to build the images and start all the services (PHP server, database, etc.) in detached mode.
    ```bash
    docker-compose up -d --build
    ```

3.  **Run Database Migrations:**
    This script sets up the necessary database tables.
    ```bash
    ./bin/migrate.sh
    ```
    *(Or: `docker-compose exec <your_php_service_name> ./bin/migrate.sh`)*

4.  **Seed the Database (Optional):**
    This script populates the database with initial sample data.
    ```bash
    ./bin/seed.sh
    ```
    *(Or: `docker-compose exec <your_php_service_name> ./bin/seed.sh`)*

5.  **Access the Application:**
    Once all containers are running, you can access the website in your browser at:
    [http://localhost:80](http://localhost:80) (Or the port you configured in your `docker-compose.yml` file).

## Project Structure

The project follows a standard MVC pattern:

├── config/         # Configuration files (e.g., database)

├── controllers/    # Handles business logic and user requests

├── models/         # Database models and data logic

├── views/          # PHP files for generating HTML

├── public/         # Public assets (CSS, JS, images)

├── Dockerfile      # Docker build file for the PHP application

└── docker-compose.yml # Defines all services (app, db, etc.)

