<?php

namespace MarJose123\Ninshiki;

use MarJose123\Ninshiki\Console\Commands\PublishCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class NinshikiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('ninshiki')
            ->hasConfigFile()
            ->hasViews()
            ->hasInertiaComponents()
            ->hasConsoleCommand(PublishCommand::class)
            ->hasRoute('web')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->startWith(function (InstallCommand $command) {
                        $command->info('Installing Ninshiki...');
                    })
                    ->publishAssets()
                    ->askToStarRepoOnGitHub('ninshiki-project/ninshiki')
                    ->endWith(function (InstallCommand $command) {
                        $command->info('Have a great day!');
                    });

            });
    }
}
