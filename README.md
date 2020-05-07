# seat-winkill
Plugin to gamble drop from killmail


## Quick Installation:

In your seat directory (By default:  /var/www/seat), type the following:

```
php artisan down

composer require akturis/seat-winkill
php artisan vendor:publish --force
php artisan migrate

php artisan up
```

And now, when you log into 'Seat', you should see a 'Win Kill' link on the left.


