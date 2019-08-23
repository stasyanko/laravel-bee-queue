# laravel-bee-queue
You think that node.js could do better at job processing than generic laravel queues? Well, then using [bee-queue](https://github.com/bee-queue/bee-queue) together with laravel is a way to go!
The package allows to create jobs for bee-queue from laravel with two lines of code:

    <?php
       $queue = new Stasyanko\LaravelBeeQueue\LaravelBeeQueue('my_queue');
	   $queue->createJob(["email" => "useremail@mail.test"]);
    ?>
    

The package is under development now and **IS NOT READY** for production!

####  TODO:

- write tests
- complete TODOs in the code
- publish to packagist

