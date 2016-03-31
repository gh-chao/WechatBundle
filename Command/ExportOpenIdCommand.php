<?php

namespace Lilocon\WechatBundle\Command;

use Lilocon\WechatBundle\Export\Exporter;
use Lilocon\WechatBundle\Export\Source\OpenId;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExportOpenIdCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('lilocon:wechat:export:openid')
            ->setDescription('export openid')
            ->addOption('format', null, InputOption::VALUE_OPTIONAL, '', 'xls')
            ->addOption('output', null, InputOption::VALUE_OPTIONAL);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->getContainer()->get('lilocon.wechat.sdk')->user;

        $source   = new OpenId($user);
        $exporter = new Exporter($source);

        $format = $input->getOption('format');
        $output = $input->getOption('output') ?: 'openid.'.$format;

        $exporter->export($format, $output);
    }
}
