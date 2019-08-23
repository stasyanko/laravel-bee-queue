# laravel-bee-queue
You think that node.js could do better at job processing than generic laravel queues? Well, then using [bee-queue](https://github.com/bee-queue/bee-queue) together with laravel is a way to go!
The package allows to create jobs for bee-queue from laravel with two lines of code:

    <?php
       $queue = new Stasyanko\LaravelBeeQueue\LaravelBeeQueue('my_queue');
	   $queue->createJob(["email" => "useremail@mail.test"]);
    ?>
    

The package is under development now and **IS NOT READY** for production!

#### Installation 

`composer require stasyanko/laravel-bee-queue`

For laravel <= 5.4:

Add in config/app.php to 'providers' section:

`Stasyanko\LaravelBeeQueue\LaravelBeeQueueServiceProvider::class
`

In order to be able to change redis settings run `php artisan vendor:publish` and set your redis credentials if they are not equal to default .env laravel keys.

####  TODO:

- write tests
- complete TODOs in the code
- publish to packagist

