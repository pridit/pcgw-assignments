<?php

require_once dirname(__DIR__) . '/bootstrap.php';

$mail = $container->mail;
$pheanstalk = $container->pheanstalk;

$container->crunz->run(function () use ($mail, $pheanstalk) {
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

return $container->crunz;
