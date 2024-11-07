Project Setup Guide

1. Clone the Project from GitHub
Open your terminal and navigate to the directory where you want to clone the project. 
Then, execute the following command:

git clone https://github.com/ZerefVlad/testLaravel.git

2. Navigate to the Project Directory
Once the project is cloned, navigate into the newly created project directory:

cd testLaravel

3. Build and Start the Docker Containers
To build and start the Docker containers, run the following command:

docker-compose up --build

This will set up all the required services (PHP, MySQL, Redis, RabbitMQ, and Nginx) as specified in the docker-compose.yml file.

4. Access the Application
Once the containers are up and running, open your web browser and go to the following link:

http://127.0.0.1:8082/

You should see the registration page of the application.
