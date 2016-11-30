<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $unitTesting = true;
        $testEnvironment = 'testing';
        $app = new Illuminate\Foundation\Application;

        $path = __DIR__ . '/../vendor/laravel/laravel';

        $app->bindInstallPaths([
            'app' => $path . '/app',
            'public' => $path . '/public',
            'base' => $path . '/',
            'storage' => $path . '/app/storage',
        ]);

        require __DIR__ . '/../vendor/laravel/framework/src/Illuminate/Foundation/start.php';
        return $app;
    }
}