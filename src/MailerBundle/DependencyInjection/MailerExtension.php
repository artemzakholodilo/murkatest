<?php

namespace MailerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MailerExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->register('emailsender', 'MailerBundle\Sender\EmailSender')
            ->addArgument(new Reference('swiftmailer.mailer'))
            ->addArgument(new Reference('swiftmailer.transport'));

        $container->register('emailnotifier', 'MailerBundle\Controller\EmailController')
                  ->addArgument(new Reference('emailsender'));
    }
}