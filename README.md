## Context

Dailymotion handles user registrations. To do so, user creates an account and we send a code by email to verify the account.

As a core API developer, you are responsible for building this feature and expose it through API.

 

## Specifications

You have to manage a user registration and his activation. In the context of this test, we consider a `BASIC AUTH` is enough to check if the user is logged in.

The API must support the following use cases:

* Create an user with an email and a password.

* Send an email to the user with a 4 digits code.

* Activate this account with the 4 digits code received.

* The user has only one minute to use this code. After that, an error should be raised.

Design and build this API. You are completely free to propose the architecture you want.

 

## What do we expect?

- PHP or Python languages are supported.

- Using frameworks is allowed only for routing, dependency injection, event dispatcher, db connection. Don't use magic! We want to see **your** implementation.

- Use the DBMS you want (except SQLite).

- Consider the SMTP server is a third party service offering an HTTP API. You can mock the call, or simply print the 4 digits in console. But do not forget in your implementation that **it is a third party service**.

- Your code should be tested.

- Your application has to run within a docker container.

- You should provide us the source code (or a link to GitHub)

- You should provide us the instructions to run your code.

- You should provide us an architecture schema.


# How to start this project : 

1. First, check if docker & docker-compose are installed on your computer.
Find here the documentation : 
    [Docker Installation](https://docs.docker.com/get-docker/)

    [Docker-comopse Installation](https://docs.docker.com/compose/install/)

2. After the first step: 
You have to build the project with this command : 

    - `docker-comopse build` (or With Makefile : `make dc-build`)
    
    Then, please to up the project containers :

    - `docker-comopse up -d` (or With Makefile : `make dc-up`)
3. Check the containers status with this command : 

    - `docker-comopse ps`
    
    If all states are Up. You can continue to the next step.
    At this point, you can access to :
    
    - [localhost phpmyadmin](http://localhost:8080/)

    - [localhost mailDev](http://localhost:8081/)
    
    - [localhost www](http://localhost:8082/)

4. You have to create the Dababase environnement, with this following commands :
    
    - To access to the symfony container : 
    
        `docker-compose exec www bash` (or With Makefile : `make dc-bash`)
    
    - Launch Composer update :
        
        `composer update`
    
    - Create Database (by default the Database is already exist in the docker container, please to skip to 5th step) : 
        
        `php bin/console doctrine:database:create`

    - Load the migration :
        
        `php bin/console doctrine:migrations:migrate`
    
    - Load the fixtures :

        `php bin/console doctrine:fixtures:load` 

5. Thats it!

6. To launch the PHPUnit test :

    `php bin/phpunit` 
        
## 

##Architecture schema :

###Entity :
- User.php
- Token.php

User and Token Entity (with User OneToMany Token DoctrineRelation)
    
###Controller :

- UserController.php

With 2 Api Routes : 

    -Route("/user/register")
    -Route("/user/{userId}/validation"
###Service :

- UserService.php
- TokenService.php
- EmailService.php

###Helper Service :
- DateTimeHelper.php
- JsonResponseHelper.php

###Test :
- UserControllerTest.php
