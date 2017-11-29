## Time and distance calculator

Tools for searching time and distance with multiple waypoint by Google Distance Matrix API.

Created by php-lumen, redis, run on docker.

### Clone this repo

```bash
git clone https://github.com/stanleybz/matt-tress.git
cd matt-tress
```

### Configuration

Go to `docker-compose.yml` update your Google API Key and nginx linked ports (default 80)

### Build & Run

```bash
docker-compose up --build -d
```

### Starting queue

```bash
docker-compose exec php php /var/www/html/app/artisan queue:listen
```

### When everything ready, go to http://YOUR_DOCKER_IP:{PORT}/{ENG_POINT} for testing

### Endpoint please refer to original document

### To stop Everything

```bash
docker-compose down
```
