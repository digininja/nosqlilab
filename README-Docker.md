# NoSQLiLab in a Docker 

A set of files to establish Docker containers for your NoSQLiLab are included in the project. With them you can quickly establish a testing range, begin your challenges, and reset them as needed. 

# Installation

1. [Install Docker CE for your platform (Windows, Linux, OSX)](https://docs.docker.com/engine/installation/)
2. [Install Docker-Compose](https://docs.docker.com/compose/install/)
3. In the top level directory, run `docker-compose build`. This will build the containers for you according to the Dockerfiles.
4. Launch the containers, run `docker-compose up`. You now have two containers running, one for the web front end and one for the MongoDB server.

You can now browse http://localhost:8080/ and get to work. Reset the database and start the challenges. 
