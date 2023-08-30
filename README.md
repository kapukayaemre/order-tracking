## About Order Tracking Restful API

This is a restful API for book merchant order and stock tracking.

## Installation Section

- You can migrate tables with `php artisan migrate` command.
- Then also you can seed these tables with `php artisan db:seed` command.


## Test Section
- You can test with Postman application.
- In files, you have a `order-tracking.postman_collection.json` just import it
on Postman then you have a ready request collection. <br/>
- Don't forget to **login** on system and save the **token** on the parent collection. 

## Queue and Cache
- index pages datas cached for 60 seconds
- product add process working with queue so after add products on queue `php artisan queue:work` command should start for save records.
