## Clone this repo

```bash
git clone https://github.com/stanleybz/matt-tress.git
cd matt-tress
```

### Configuration

Go to `docker-compose.yml` update your Google API Key

### Build & Run

```bash
docker-compose up --build -d
```

Navigate to [http://localhost:80](http://localhost:80) and you should see something like this

### Updating vendor

```bash
docker-compose exec php composer update -d /var/www/html/app
```

### Starting queue

```bash
docker-compose exec php php /var/www/html/app/artisan queue:listen
```

### Stop Everything

```bash
docker-compose down
```
