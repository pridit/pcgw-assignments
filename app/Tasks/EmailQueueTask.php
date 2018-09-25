<?php

use App\Helpers\Mail;

use Crunz\Schedule;
use Pheanstalk\Pheanstalk;

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../../');
$dotenv->load();

$config = new \Noodlehaus\Config(dirname(__DIR__) . '/../config/mail.php');

$schedule = new Schedule();

$schedule->run(function () use ($config) {
    $mail = new Mail($config);
    $pheanstalk = new Pheanstalk('127.0.0.1');

    while (true) {
        $job = $pheanstalk->reserveFromTube('email', 0);

        if (!$job) {
            break;
        }

        $mail->doNew(json_decode($job->getData(), true));

        $pheanstalk->delete($job);
    }
})
->everyMinute();

return $schedule;
