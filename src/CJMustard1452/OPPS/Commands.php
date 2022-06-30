<?php

namespace CJMustard1452\OPPS;

use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class Commands implements Listener{

	/** @var Server */
	public $getServer;
	public $getPlugin;
	public $toggleFile;

	public function __construct(){
		$this->getServer = Server::getInstance();
		$this->getPlugin = $this->getServer->getPluginManager()->getPlugin("OPPS");
		$this->toggleFile = new Config($this->getPlugin->getDataFolder() . "Toggles", Config::YAML);
	}

	public function onCommand(CommandEvent $event){
		if(explode(" ", $event->getCommand())[0] === "opps"){
			$sender = $event->getSender();
			$args = explode(" ", $event->getCommand());
			if($sender instanceof Player){
				if(isset($args[1]) && strtolower($args[1]) == "glock"){
					$sender->sendMessage("§3[§4OPPS+§3]§3 You have been given a §6Glock 9§3.");
					$sender->getInventory()->addItem(Item::get(290)->setLore(["Glock"])->setCustomName("§6Glock 9"));
				}elseif(isset($args[1]) && strtolower($args[1]) == "semi"){
					$sender->sendMessage("§3[§4OPPS+§3]§3 You have been given a §6Semi-Automatic§3.");
					$sender->getInventory()->addItem(Item::get(291)->setLore(["Semi-Automatic"])->setCustomName("§6Semi-Automatic"));
				}elseif(isset($args[1]) && strtolower($args[1]) == "machine"){
					$sender->sendMessage("§3[§4OPPS+§3]§3 You have been given a §6Machine-Gun§3.");
					$sender->getInventory()->addItem(Item::get(292)->setLore(["Machine-Gun"])->setCustomName("§6Machine-Gun"));
				}elseif(isset($args[1]) && strtolower($args[1]) == "ak47"){
					$sender->sendMessage("§3[§4OPPS+§3]§3 You have been given a §6AK-47§3.");
					$sender->getInventory()->addItem(Item::get(294)->setLore(["AK-47"])->setCustomName("§6AK-47"));
				}elseif(isset($args[1]) && strtolower($args[1]) == "benz"){
					$this->toggleFile->reload();
					if($this->toggleFile->get($sender->getName() . "spider") == null){
						$sender->sendMessage("§3[§4OPPS+§3]§3 You have spawned §6Rideable Spider§3.");
						$this->getPlugin->createSpider($sender);
						$this->toggleFile->set($sender->getName() . "spider", true);
						$this->toggleFile->save();
					}else{
						$sender->sendMessage("§3[§4OPPS+§3]§3 You already have a Spider-Benz.");
					}
				}else{
					$sender->sendMessage("§3[§4OPPS+§3]§3 /opps (Glock | Semi | Machine | AK47)");
				}
			}else{
				$sender->sendMessage("You can only run this command in game.");
			}
		}
	}
}