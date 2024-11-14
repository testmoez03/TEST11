### 0- Pull them images form docker hub

```
docker pull testmoez/theme1:latest
docker pull testmoez/theme2:latest
```

### 1- Create a new docker network named "traefik-network"

```
docker network create --driver bridge traefik-network
```

### 2 - Download Traefik in Docker

```
docker compose up -d
```

### 3 - Run the laravel app

```
copy .env.example to .env and edit as needed.
composer install --no-dev
php artisan serve

```

### if you are using Linux or macos you have to uncomment lines 38 to 44 in app\Http\Controllers\ThemeController.php

### And comment lines 29 to 35 in the same file
