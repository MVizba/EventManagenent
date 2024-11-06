EVENT MANAGEMENT APP

Preparation:

1. Install dependencies:
    - Composer install

2. Build a docker containers:
    - docker-compose up -d --build
   
3. Rename .env.example to .env
    - add secret for APP_SECRET:
    - Update DATABASE_URL with your credentials:

4. Enter container and run migration:
    - docker exec -it eventmanagementapp-eventplanner-1 bash
    - php bin/console doctrine:migrations:migrate


Routes:
run on localhost:8080/

* /events route (events_list): Displays a list of all events.
* /events/{id}/register route (event_register): Allows users to register for a specific event.
* /events/new route (event_new): Provides a form to create a new event.

Console:

1. Get into Docker container:
   docker exec -it eventmanagementapp-eventplanner-1 bash

2. Run Command ListRegisteredUsersCommand.php:
   php bin/console app:list-registered-users
