<?php
namespace Carbonclick\CFC\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Carbonclick\CFC\Model\Service\Cfc\CreateShop;
use Carbonclick\CFC\Model\CreateProduct;
use Magento\Framework\App\State;
use Symfony\Component\Console\Exception\LogicException;

class Postpaid extends Command
{
	/**
     * @var CreateShop
     */
    private $createshop;

    /**
     * @var CreateProduct
     */
    private $createproduct;

    /**
     * @var State
     */
    private $state;

	/**
     * Constructor
     *
     * @param CreateShop $createshop
     */
    public function __construct(
    	CreateShop $createshop,
    	State $state,
    	CreateProduct $createproduct
    ) {
        $this->createshop = $createshop;
        $this->state = $state;
        $this->createproduct = $createproduct;
        parent::__construct();
    }

   	protected function configure()
   	{
       	$this->setName('carbonclick:postpaid');
       	$this->setDescription('Set postpaid payment method for carbonclick plugin.');
       
       	parent::configure();
   	}
   	protected function execute(InputInterface $input, OutputInterface $output)
   	{
       	$shopdata = $this->createshop->getShop();
       	if($shopdata){
       		throw new LogicException('You are not allowed to change the payment option.');
       	}else{
       		$shop = $this->createshop->CreateShop("postpaid");
       		if(!empty($shop)){
       			$this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
       			$this->createproduct->CreateProduct();
       		}
       		$output->writeln("Shop is created successfully.");
       	}
   	}
}