# Loan App

**Prerequisites**

- Install composer 2.0.
- Install docker (latest version), docker-compose (latest version).
- Linux OS required. This project runs with Laravel Sail, so it is best to run in a Linux OS machine. I already tried with WSL2, but it is having an issue with this platform.

---------------
**Project setup**

1. At the root of the project, run the following command to install the project's dependencies
````
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
````

2. Initialize docker containers using Laravel Sail:

- Configure a Bash alias which allows you to execute Sail's commands more easily, run the following command:

````  
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
````

- Then Sail is now ready to use with:

````
sail up -d // Initialize the containers (detached mode)

sail down -v // Stop the containers, remove volumes
````

3. Once all containers are up and running (this might take several minutes to install necessary dependencies of the project)

4. Run migration with the below command

````
sail artisan migrate
````

5. Set up laravel passport
````
sail artisan passport:install // Initialize the client ID and client secret
````

6. Refer to the API guidelines section to learn more about the usage of each API.

**Database**

- Navigate to the following URL:

``http://localhost:8081``

- Use the credentials in the .env file to access phpmyadmin:

````
  DB_USERNAME=sail
  
  DB_PASSWORD=password
````

**Unit Testing**

- Run unit test with the following command:

````
docker exec -it simple-api_laravel.test bash
````

**API Guidelines**

The base URL of all APIs is: http://localhost/api/v1 . Except for Authentication API, **the v1 segment is not included**.

1. Authentication API:

- POST /register : Create a new user and also provide a token.


    + Body: form-data
    
    + name: Tuan, email: npatuan.uit@gmail.com, password: test, password_confirmation: test, role: admin

- POST /login : Authenticate a user created from the registration API above. Generate access token for the given user. Use the access token provided by this API to use the APIs below. Please set the Authorzation Header: Bearer {access_token} for every API request.


    + Body: form-data

    + email: npatuan.uit@gmail.com, password: test

2. Loan API: 

- GET /loans/{loanId} : Fetch details of a specific loan. Users will not be able to see details of other users loans.


- POST /loans : Create a loan from customer's request.

    Sample payload:

    <code>{
  "term": 3
  }</code>


- PATCH /repayments/{id} : Update a repayment by repayment id.

    Sample payload:

   <code>{
  "amount": 3333
  }</code>


- POST /admin/loans/{id}/approve : Approve a pending loan by loan id.
