# Data Visulization App



Akram Abbasi: mohdakramabbasi@gmail.com
```

### Installation
* Run `git clone https://github.com/akramaliabbasi/data-visulization-app.git`
* `cd data-visulization-app` 
* Run `composer install` (install composer beforehand)
* From the projects root run `cp .env.example .env`
* Configure your `.env` file, with:
* Run `php artisan config:clear`

Database settings
```
DB_DATABASE=data_visualization
DB_USERNAME=root
DB_PASSWORD=
```


Email settings (using a provider like Mailgun, Amazon SES, etc)

* Run `php artisan key:generate`
* Run `php artisan migrate `
* For Auth API (to configure Laravel Passport), run: `php artisan passport:install`
* Run `npm install && npm run dev`
* Run `php artisan db:seed` or `php artisan --seed`
* Run `php artisan optimize:clear`
* Run `php artisan config:clear` #if you updated the config file
* Run `php artisan route:clear` 

* Start the Laravel server `php artisan serve --port=8000`

* Start the Websocket server (for chat functionality) `php artisan websockets:serve`




### License
Data Visulization App licensed under the MIT license. Enjoy!


