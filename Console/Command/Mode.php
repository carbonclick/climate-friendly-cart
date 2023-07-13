<?php
namespace Carbonclick\CFC\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Carbonclick\CFC\Model\CreateProduct;
use Carbonclick\CFC\Model\Service\SaveDashboard;
use Magento\Framework\App\State;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Exception\LogicException;

class Mode extends Command
{

    const NAME_ARGUMENT = "mode";
    /**
     * @var CreateProduct
     */
    private $createproduct;

    /**
     * @var State
     */
    private $state;

    /**
     * @var SaveDashboard
     */
    private $saveconfig;

    /**
     * Constructor
     *
     * @param CreateShop $createshop
     */
    public function __construct(
        SaveDashboard $saveconfig,
        State $state,
        CreateProduct $createproduct
    ) {
        $this->state = $state;
        $this->saveconfig = $saveconfig;
        $this->createproduct = $createproduct;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('carbonclick:set:mode');
        $this->setDescription('Set Mode for carbonclick plugin.');
        $this->setDefinition([
            new InputArgument(self::NAME_ARGUMENT, InputArgument::OPTIONAL, "Mode")
        ]);
        parent::configure();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($name = $input->getArgument(self::NAME_ARGUMENT)) {
            $mode = $this->saveconfig->getConfig('cfc/general/mode');
            if($name == "dev" && $mode == 2){
                $output->writeln('<info>Mode is set ' . $name . '</info>');
            }else if($name == "live" && $mode == 1){
                $output->writeln('<info>Mode is set ' . $name . '</info>');
            }else if($name == "dev"){
                $this->saveconfig->saveConfig('cfc/general/shop','');
                $this->saveconfig->saveConfig('cfc/general/mode',2);
                $this->saveconfig->saveConfig('cfc/general/enable',0);
                $this->createproduct->UpdateStatus(0);
                $output->writeln('<info>Mode is set ' . $name . '</info>');
            }else if($name == "live"){
                $this->saveconfig->saveConfig('cfc/general/shop','');
                $this->saveconfig->saveConfig('cfc/general/mode',1);
                $this->saveconfig->saveConfig('cfc/general/enable',0);
                $this->createproduct->UpdateStatus(0);
                $output->writeln('<info>Mode is set ' . $name . '</info>');
            }
        }else{
            throw new LogicException('Please select mode.');
            return Command::INVALID;
        }
        return Command::SUCCESS;
    }
}
