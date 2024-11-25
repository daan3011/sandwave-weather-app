
# Sandwave technical assignment

A Weather monitoring application built with Laravel 11 and Vue 3, provides real-time weather data, forecasts, and air quality information. This application leverages Docker for seamless local development.

# Table of contents
1. [Features](#features)
2. [Techstack](#tech)
3. [Prerequisites](#prerequisites)
4. [Installation](#installation)
5. [Environment Variables](#environment-variables)
6. [API Reference](#api-reference)
7. [Running Tests](#running-tests)
8. [Contributing](#contributing)
9. [License](#license)

## Features

- Real-Time Weather Data: Fetch current weather conditions for any city.
- 5-Day Forecast: Get detailed weather forecasts up to five days ahead.
- Air Quality Index: Monitor air quality parameters in selected locations.
- Create weather monitors to monitor weather changes over time. 
- Caching Mechanism: Efficient data retrieval with caching to reduce API calls.
- Rate Limiting: Protects the API endpoints from abuse and ensures fair usage.
- Containerized Environment: Simplifies setup and ensures consistency across development environments.
- API Documentation: Comprehensive API docs available at /request-docs.
## Tech Stack

**Server:** Laravel 11

**Client:** Vue 3, TailwindCSS

**Database:** SQLite

**Caching:** Laravel Cache (Database driver)

**Containerization:** Docker, Docker Compose

**Testing** Pest PHP


## Prerequisites

Before getting started, ensure you have the following installed on your system:

- Docker
- Docker Compose
- Git
- Node.js & npm (for frontend asset management)
## Installation
Follow these steps to set up the Weather App locally using Docker:

Clone the project

```bash
  git clone git@github.com:daan3011/sandwave-weather-app.git
```
Move into the project directory
```bash
  cd sandwave-weather-app
```
Build the container, use the -f flag the specify the location of the docker compose file
```bash
  docker compose -f ./docker/docker-compose.yml build
```
Run the containers, use the -d flag to run in detached mode
```bash
  docker compose -f ./docker/docker-compose.yml up
``` 
Notice:the entrypoint script of the container:
- copies .env.example to .env
- installs composer dependencies
- installs npm dependencies
- generates the application key
- sets up the sqlite database
- runs the migrations
- builds frontend assets for production
- starts the laravel queue and schedule via supervisord
- starts nginx and php-fpm 

Notice: this is a development container and is meant for local development only. The project directory is mounted as a volume so changes are immediately reflected.

To watch for frontend changes run:
```bash
  docker exec -it weather-app php npm run dev
``` 
## Environment Variables

To run this project, you will need to add the following environment variables to your .env file

`OPENWEATHERMAP_API_KEY`

Optional environment variables, these environment variables have defaults set which can be editted by setting them in .env or modifying the config/weather.php configuration file

`WEATHER_OVERVIEW_CACHE_TTL`

`OPENWEATHERMAP_API_URL`

`OPENWEATHERMAP_GEO_API_URL`



## API Reference

1. Accessing API Docs
Once the Docker containers are up and running, the API documentation is accessible at:

```bash
  http://localhost:8080/request-docs
``` 
Note: Ensure that the application is running and accessible on port 8080.

#### Weather data

```http
  GET /api/weather
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `city` | `string` | **Required**. Valid city name |

get weather data for a city

#### Weather Monitors

```http
  GET /api/weather-monitors
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |

list all weather data

```http
  POST /api/weather-monitors
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `city` | `string` | **Required**. Valid city name |
| `interval` | `int` | **Required**. Interval to pull data on |

Create a new weather monitor

```http
  GET /api/weather-monitors/{monitor_id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `monitor_id` | `int` | **Required**. Weather monitor id |

Get specific weather monitor

```http
  DELETE /api/weather-monitors/{monitor_id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |
| `monitor_id` | `int` | **Required**. Weather monitor id |

Delete a weather monitor

#### Weather Readings

```http
  GET /api/weather-readings
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :------------------------- |

Get paginated weather readings

## Rate Limiting
To ensure fair usage and protect the API from abuse, the application implements rate limiting via the RateLimitServiceProvider.

Configuration
File: app/Providers/RateLimitServiceProvider.php

## Caching
caching reduces redundant API calls, the /weather API call is cached for 5 minutes to ensure a balance between up-to-date information and optimized performance.

#### Key Components:
- **OpenWeatherMapServiceInterface:** Defines the contract for weather data services.
- **OpenWeatherMapService:** Implements the interface, directly handling API interactions with OpenWeatherMap.
- **CachingOpenWeatherMapService:** Acts as a decorator for the original service, introducing caching functionality to store and reuse API responses.
## Running Tests with Pest

To run the tests in the container, run the following command
```bash
  docker exec -it weather-app php artisan test
```
To speed up the process and run tests in parralel use
```bash
  docker exec -it weather-app php artisan test --parallel
```
## Contributing
Contributions are welcome! If you have suggestions or improvements, feel free to create an issue or submit a pull request.

Steps to Contribute:

#### Fork the Repository:

Click on the "Fork" button at the top-right corner of the repository page.

#### Clone Your Fork:
```bash
  git clone https://github.com/yourusername/weather-app.git
```

#### Move into project:
```bash
  cd weather-app
```

#### Create a Feature Branch:
```bash
  git checkout -b feature/YourFeatureName
```

#### Make Your Changes:

- Implement your feature or fix.

#### Commit Your Changes:
```bash
  git commit -m "Add feature: YourFeatureName"
```

#### Push to Your Fork:
```bash
  git push origin feature/YourFeatureName
```

#### Create a Pull Request:
- Navigate to the original repository and click on "Compare & pull request."

## Guidelines:
- **Code Standards:** Follow PSR standards and Laravel's best practices.
- **Testing:** Ensure that all tests pass and add new tests for your changes.
- **Documentation:** Update documentation as necessary.
## License
This project is open-source and available under the MIT License.
[MIT](https://choosealicense.com/licenses/mit/)

