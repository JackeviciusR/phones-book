## Phones Book

#### in progress...

### Used:
- GUI
- Docker
- Authetification
- CRUD
- OOP

#### Comming soon
- Refractoring
- Managers
- Repositories
- Unit test
- Feature test
- API

### Application running
After download:
```
cd application
composer install
npm install
npm run dev

cd ..
docker compose up -d
```
Make migration and seed:
- run web container:
`docker exec -it web_image_name bash`
- migrate fresh and seed data: 
`php artisan migrate:fresh --seed`








