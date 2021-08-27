## Phones Book

#### in progress...

### Used:
- GUI
- Docker
- Authentication
- Protected others users route parameters
- CRUD
- OOP
- Dependency injection
- Managers, Repositories
- Tests

#### Comming soon
- Refractoring
- Managers (more)
- Repositories (more)
- Unit test (more)
- Feature test (more)
- API

### Application running

with Docker |
------------|

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

web:| http://localhost/login |
----|------------------------|
email|`username@gmail.com`|
password|`slaptazodis`|






