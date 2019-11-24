# Readme

The URL server must be 

http://YOUR_SERVER/ONLINEBERATUNG

1.  First, ensure that PHP is configured to allow file uploads. 
	In your "php.ini" file, search for the file_uploads directive, and set it to On:  file_uploads = On
2.  Set the right permision to the project folder,  in unix system must be 755.
	sudo chown www-data:www-data /var/www/html/ONLINEBERATUNG
	sudo chmod -R 755 /var/www/html/ONLINEBERATUNG
3. Copy the project in root
4. Be sure your php is config, use http://SERVER_DIR/ONLINEBERATUNG/p.php you will se phpinfo() result

the solution work as a single page aplication, to enable F5 clear url you must activate Apache Module mod_rewrite
http://httpd.apache.org/docs/current/mod/mod_rewrite.html 

command   sudo a2enmod rewrite  can help you.

be sure you follow Angular instruction
https://angular.io/guide/deployment

# ConferencePlanner

This project was generated with [Angular CLI](https://github.com/angular/angular-cli) version 7.3.9.

## Development server

Run `ng serve` for a dev server. Navigate to `http://localhost:4200/`. The app will automatically reload if you change any of the source files.

## Code scaffolding

Run `ng generate component component-name` to generate a new component. You can also use `ng generate directive|pipe|service|class|guard|interface|enum|module`.

## Build

Run `ng build` to build the project. The build artifacts will be stored in the `dist/` directory. Use the `--prod` flag for a production build.

## Running php in test

Run php -S 127.0.0.1:8080 -t ./src/backend/ from the main project folder to start running php as api server, You can find class in model folder, and spacial algoritm with recursive soluticion in a Algorithm Class.

