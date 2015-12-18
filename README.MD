#WordPress Admin JavaScript Single Page Application Boilerplate
 My intention or this project is to be an easy starting point for anyone wanting to create a WordPress admin that is a JavaScript SPA.
 Right now this is a standalone __WordPress__ plugin.

##AngularJS
The main repo will be AngularJS, I will then fork or branch to create a ReactJS version and other version based on demand

##Installation
* Clone Repo into `wp-content/plugins`
* Run `npm install`
* Run `gulp`

##Starter Content
I have included a CPT of 'Book' to get created with 10 posts, to turn this off comment out lines 64-65 of `admin-js-app.php`

##Custom API Endpoints
In `includes/admin_custom_api.php` you will find the custom api endpoints I have created, I have kept it simple so you can modify or copy to create your own

##Routes & Templates
All html templates are located in `templates/`
Default routes include:
* `/` main page - __admin-js-app-main.html__
* `/books` listing page - __admin-js-app-list.html__
* `/book/:id` detail page - __admin-js-app-detail.html__
* `/book/:id/edit` edit page - __admin-js-app-edit.html__

