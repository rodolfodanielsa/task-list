# Task List

## Setup

### Clone project
`git clone git@github.com:rodolfodanielsa/task-list.git`

### Setup Docker Containers
`cd task-list`

Copy `.env.[OS]` to `.env` according to your Operating System

`docker-compose up -d`

### Project Initial Setup (with DB mocked data)

Copy `code/.env.example` to `code/.env`

`docker exec php composer install`

`docker exec php php artisan migrate`

### Tests

Run `docker exec php ./vendor/phpunit/phpunit/phpunit tests/`

### Endpoints
`GET /users`

Returns all users and their roles

`GET /tasks/user/{userId}`

Get Tasks by userId. Manager role will get all tasks.

`POST /task/user/{userId}`

Add a Task into the system. Task notification is saved in `code/storage/logs/user-tasks.log`

### Environment

#### url
http://localhost:8080/

#### ports

- php: 9000
- nginx: 8080
- mysql: 3306