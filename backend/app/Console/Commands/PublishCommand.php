<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:publish {msg} {topic=command}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send a command to mqtt broker';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (blank($this->argument('msg'))){
            $this->error('You can not send empty msg!!');
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

        $mqtt->publish($this->argument('topic'), $this->argument('msg'), 0);
        $mqtt->disconnect();
    }
}
