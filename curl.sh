#!/bin/sh

#### BOOKS ####
curl -X POST http://localhost:9501/books \
     -H "Content-Type: application/json" \
     -d '{
          "title": "Clean Code",
          "publisher": "Prentice Hall",
          "edition": 1,
          "publication_year": 2008
          "authors": [2],
          "subjects": [2,3]
        }'
response: {"success":true,"data":{"title":"Clean Code","publisher":"Prentice Hall","edition":1,"publication_year":2008,"updated_at":"2025-03-21 14:57:25","created_at":"2025-03-21 14:57:25","id":1}}

curl -v -X GET http://localhost:9501/books -H "Accept: application/json"
response: {"success":true,"data":{"id":1,"title":"Refactoring","publisher":"Addison-Wesley","edition":2,"publication_year":2018,"created_at":"2025-03-21 14:57:25","updated_at":"2025-03-21 14:59:44"}}

curl -X GET http://localhost:9501/books/1 -H "Accept: application/json"
response: {"success":true,"data":{"id":1,"title":"Refactoring","publisher":"Addison-Wesley","edition":2,"publication_year":2018,"created_at":"2025-03-21 14:57:25","updated_at":"2025-03-21 14:59:44"}}

curl -X PUT http://localhost:9501/books/1 \
     -H "Content-Type: application/json" \
     -d '{
          "title": "Refactoring",
          "publisher": "Addison-Wesley",
          "edition": 2,
          "publication_year": 2018
        }'
response: {"success":true,"message":"Book updated successfully"}

curl -X DELETE http://localhost:9501/books/1
response: {"success":true,"message":"Book deleted successfully"}

#### SUBJECTS ####

curl -X POST http://localhost:9501/subjects \
     -H "Content-Type: application/json" \
     -d '{
          "description": "Science Fiction"
        }'

response: {"success":true,"data":{"description":"Science Fiction","updated_at":"2025-03-21 15:12:40","created_at":"2025-03-21 15:12:40","id":1}}

curl -X GET http://localhost:9501/subjects -H "Accept: application/json"
response: {"success":true,"data":[{"id":1,"description":"Science Fiction","created_at":"2025-03-21 15:12:40","updated_at":"2025-03-21 15:12:40"}]}

curl -X GET http://localhost:9501/subjects/1 -H "Accept: application/json"
response: {"success":true,"data":{"id":1,"description":"Science Fiction","created_at":"2025-03-21 15:12:40","updated_at":"2025-03-21 15:12:40"}}

curl -X PUT http://localhost:9501/subjects/1 \
     -H "Content-Type: application/json" \
     -d '{
          "description": "Fantasy"
        }'
response: {"success":true,"message":"Subject updated successfully"}

curl -X DELETE http://localhost:9501/subjects/1
response: {"success":true,"message":"Subject deleted successfully"}

#### AUTHORS ###

curl -X POST http://localhost:9501/authors \
     -H "Content-Type: application/json" \
     -d '{
          "name": "J.K. Rowling"
        }'
response: {"success":true,"data":{"name":"J.K. Rowling","updated_at":"2025-03-21 15:32:31","created_at":"2025-03-21 15:32:31","id":1}}

curl -X GET http://localhost:9501/authors -H "Accept: application/json"
response: {"success":true,"data":[{"id":1,"name":"J.K. Rowling","created_at":"2025-03-21 15:32:31","updated_at":"2025-03-21 15:32:31"}]}

curl -X GET http://localhost:9501/authors/1 -H "Accept: application/json"
response: {"success":true,"data":{"id":1,"name":"J.K. Rowling","created_at":"2025-03-21 15:32:31","updated_at":"2025-03-21 15:32:31"}}

curl -X PUT http://localhost:9501/authors/1 \
     -H "Content-Type: application/json" \
     -d '{
          "name": "George R.R. Martin"
        }'
response: {"success":true,"message":"Author updated successfully"}

curl -X DELETE http://localhost:9501/authors/1
response: {"success":true,"message":"Author deleted successfully"}
