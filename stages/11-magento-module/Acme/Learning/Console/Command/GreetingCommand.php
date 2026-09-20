<?php

declare(strict_types=1);

namespace Acme\Learning\Console\Command;

use Acme\Learning\Model\Greeting;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

// extends 继承 Symfony 的命令基础类；框架在命令生命周期调用 configure/execute，不是自己循环调用。
final class GreetingCommand extends Command
{
    // private const 是仅本类使用的常量；self::NAME_OPTION 取它的值。
    private const NAME_OPTION = 'name';

    // 构造器接收 Greeting 服务并存为私有只读属性；依赖注入由 Magento 创建/传入对象，不是语法自动注入。
    public function __construct(private readonly Greeting $greeting)
    {
        // parent:: 调父类构造函数，保留命令初始化步骤。
        parent::__construct();
    }

    protected function configure(): void
    {
        // protected 供本类/继承链使用；链式调用设置命令名、说明和选项。
        // addOption 参数为名称、快捷名(null 表示没有)、模式、说明；VALUE_REQUIRED 表示一旦给选项就必须带值。
        $this->setName('learning:greet')
            ->setDescription('Verify that the Acme_Learning module is active.')
            ->addOption(self::NAME_OPTION, null, InputOption::VALUE_REQUIRED, 'Name to greet');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // getOption 读 --name；writeln 输出并换行。返回 Command::SUCCESS 是成功退出码 0。
        $name = $input->getOption(self::NAME_OPTION);
        $output->writeln($this->greeting->forName(is_string($name) ? $name : ''));

        return Command::SUCCESS;
    }
}
