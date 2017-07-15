You could visit the page: jiyongcheng.me for a quick look

You also could download the project to local have a test

1.git clone https://github.com/jiyongcheng/test.git 

or download it from github

2.cd to the project root folder and run:

**composer install**

3.if you use mysql, create a databse, for example: phptest

4.cd to project root folder, create .env file

you could run : 

**cp  .env.test .env**

and make some change in .env file as follows:

DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=phptest

DB_USERNAME=YOUR_DATABASE_USERNAME

DB_PASSWORD=YOUR_DATABASE_PASSWORD

5.if you use sqlite, cd to project root folder and run:

**cp .env.sqlite .env**

then create a file : database.sqlite in the project folder database.

and make some changes in .env file as follows:

DB_CONNECTION=sqlite
DB_DATABASE=YOUR ABSOLUTE PATH OF FILE database.sqlite, like :/Users/Aaron/Sites/test/database/database.sqlite
or E:\test\database\database.sqlite

6.migreate tables

**php artisan migrate**

7.if you want to populate some data

**php artisan db:seed**

8.cd to project root folder, and run:

**php artisan serve**

then you will got a url maybe like : 127.0.0.1:8000, just use browser open this url and visit the page.

9.if you want to run the phpunit test, cd to the project root folder, and run:

**./vendor/bin/phpunit**


something maybe need to pay attention:

(1) folder privileges 

chown -R www-data:www-data test