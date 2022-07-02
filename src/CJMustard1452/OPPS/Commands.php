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
			$args =   explode(" ", $event->getCommand());
			if($sender instanceof Player){
				if($sender->hasPermission("opps.admin")){
					if(isset($args[1]) && (strtolower($args[1]) == "glock" || strtolower($args[1]) == "semi" || strtolower($args[1]) == "machine" || strtolower($args[1]) == "ak47" || strtolower($args[1]) == "rpg" || strtolower($args[1]) == "benz")){
						switch(strtolower($args[1])){
							case "glock":
								$sender->sendMessage("§3[§4OPPS+§3]§3 You have been given a §6Glock 9§3.");
								$sender->getInventory()->addItem(Item::get(290)->setLore(["Glock"])->setCustomName("§6Glock 9"));
								break;
							case "semi":
								$sender->sendMessage("§3[§4OPPS+§3]§3 You have been given a §6Semi-Automatic§3.");
								$sender->getInventory()->addItem(Item::get(291)->setLore(["Semi-Automatic"])->setCustomName("§6Semi-Automatic"));
								break;
							case "machine":
								$sender->sendMessage("§3[§4OPPS+§3]§3 You have been given a §6Machine-Gun§3.");
								$sender->getInventory()->addItem(Item::get(292)->setLore(["Machine-Gun"])->setCustomName("§6Machine-Gun"));
								break;
							case "ak47":
								$sender->sendMessage("§3[§4OPPS+§3]§3 You have been given a §6AK-47§3.");
								$sender->getInventory()->addItem(Item::get(294)->setLore(["AK-47"])->setCustomName("§6AK-47"));
								break;
							case "rpg":
								$sender->sendMessage("§3[§4OPPS+§3]§3 You have been given a §6Rocket-Launcher§3.");
								$sender->getInventory()->addItem(Item::get(294)->setLore(["RPG"])->setCustomName("§6Rocket-Launcher"));
								break;
							case "benz":
									$sender->sendMessage("§3[§4OPPS+§3]§3 You have summoned a §6Spider-Benz§3.");
									$this->getPlugin->createSpider($sender);
								break;
						}
					}else{
						$sender->sendMessage("§3[§4OPPS+§3]§3 /opps (Glock | Semi | Machine | AK47 | RPG) or (benz)");
					}
				}
			}else{
				$sender->sendMessage("You can only run this command in game.");
			}
		}
	}
}