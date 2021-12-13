
Laravel Backend with Passport is a simple Laravel base project to build an CRUD API backend with Password authentication.

## Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Passport

Laravel Passport provides a full OAuth2 server implementation for your Laravel application in a matter of minutes. Passport is built on top of the [League OAuth2 server](https://github.com/thephpleague/oauth2-server) that is maintained by Andy Millington and Simon Hamp.

## Installation
Clone the respository to your local folder:

```#> git clone git@github.com:ramoncardena/laravel-backend-passport.git my-project-folder```

Move to you new folder:

`#> cd my-project-fodlder`

Launch Sail:

`#> sail up`


## Initialization
Remember to create and update your `.env` file

Execute the following commands to initialize your app:

```
#> sail composer install
#> sail artisan key:generate
#> sail artisan cache:clear
#> sail artisan config:clear
#> sail artisan optimize
#> sail artisan migrate
```


## Data Model
The project comes with an example `Portfolio` model.

The model has just 2 fields: `name` and `description`.

You can Create, Retrieve, Update and Delete portfolios with the corresponding HTTP requests.

## Routes
You have the following routes available for **users**.

Unauthenticated:

```
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
```

Authenticated:

```
Route::get('/', [AuthController::class, 'user']);
Route::get('logout', [AuthController::class, 'logout']);
```


You have the following routes available for **portfolios**.

Authenticated:

```
Route::resource('portfolios', PortfolioController::class);
```

Actions Handled By Portfolio Resource Controller:

GET			/portfolios 		index		portfolios.index
POST		/portfolios			store		portfolios.store
GET			/portfolio/{id}		show		portfolios.show
PUT/PATCH	/portfolio/{id}		update		portfolios.update
DELETE		/portfolio/{id}		destroy		portfolios.destroy


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
