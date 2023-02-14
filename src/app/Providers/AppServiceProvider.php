<?php

namespace App\Providers;

use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use App\Actions\ExportEventRegistrationsAction;
use App\Actions\ExportTrainingRegisrationsAction;
use Google\Client;
use Google\Service\Drive;
use Masbug\Flysystem\GoogleDriveAdapter;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Filesystem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Voyager::addAction(ExportTrainingRegisrationsAction::class);
        Voyager::addAction(ExportEventRegistrationsAction::class);
        Storage::extend('google', function($app, $config) {
            $options = [];

            if (!empty($config['teamDriveId'] ?? null)) {
                $options['teamDriveId'] = $config['teamDriveId'];
            }

            $client = new Client;
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);
            
            $service = new Drive($client);
            $adapter = new GoogleDriveAdapter($service, $config['folder'] ?? '/', $options);
            $driver = new Filesystem($adapter);

            return new FilesystemAdapter($driver, $adapter);
        });
    }
}
