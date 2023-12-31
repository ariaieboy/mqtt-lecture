<?php

namespace App\Console\Commands;

use App\Models\Signal;
use Illuminate\Console\Command;

class Subscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:subscribe {topic=signal}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'subscribe to a topic';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (blank($this->argument('topic'))){
            $this->error('You can not send empty topic!!');
            return;
        }
        $server   = config('app.mqtt.server');
        $port     = config('app.mqtt.port');
        $username = config('app.mqtt.username');
        $password = config('app.mqtt.password');

        $mqtt = new \PhpMqtt\Client\MqttClient($server, $port);

        $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
            ->setUseTls(true)
            ->setUsername($username)
            ->setPassword($password);
        $mqtt->connect($connectionSettings, true);
        $mqtt->subscribe($this->argument('topic'), function (string $topic, string $message, bool $retained) {
            Signal::create([
                'topic'=>$topic,
                'message'=>$message
            ]);
        },0);
        $mqtt->loop();
    }
}
