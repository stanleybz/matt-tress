## Clone this repo

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

### Updating vendor

```bash
docker-compose exec php composer update -d /var/www/html/app
```

### Starting queue

```bash
docker-compose exec php php /var/www/html/app/artisan queue:listen
```

Everything ready, go to http://YOUR_DOCKER_IP:80/{end_point} for testing

### Stop Everything

```bash
docker-compose down
```
