<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\DependencyInjection\ContainerBuilder; 
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function process(ContainerBuilder $container) : void {
        // indicate what we want to NOT reboot or reset
        if('test' === $this->environment) {
            // prevent security token to be clear
            $container->getDefinition('security.token_storage')
            ->clearTag('kernel.reset');
            // prevent reset of doctrine
            $container->getDefinition('doctrine')
            ->clearTag('kernel.reset');
            $container->getDefinition('doctrine.orm.entity_manager')
            ->clearTag('kernel.reset');
        }
    }
}
