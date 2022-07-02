<?php

namespace CJMustard1452\OPPS\Entities;

use pocketmine\entity\projectile\Egg;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class SpiderAI implements Listener{
	/** @var Server */
	public $getServer;
	public $getPlugin;
	public $toggleFile;

	public function __construct(){
		$this->getServer = Server::getInstance();
		$this->getPlugin = $this->getServer->getPluginManager()->getPlugin("OPPS");
		$this->toggleFile = new Config($this->getPlugin->getDataFolder() . "Toggles", Config::YAML);
	}

	public function onMovement(PlayerMoveEvent $event){
		try{
		$player = $event->getPlayer();
		foreach($event->getPlayer()->getLevel()->getEntities() as $entity){
			if($entity->getOwningEntityId() == $event->getPlayer()->getId()){
				$entity->setPositionAndRotation($player->getPosition(), $player->getYaw(), $player->getPitch());
			}
		}
		}catch(\Throwable $e){
			return;
		}
	}
	public function onClick(EntityDamageEvent $event){
		try{
		if($event->getCause() == 1){
			if($event->getDamager() instanceof Player){
				if($event->getDamager()->hasPermission("opps.admin")){
				$this->toggleFile->reload();
				if($this->toggleFile->get($event->getDamager()->getName() . "-SpiderToggle") == null){
					if($event->getEntity()->getNameTag() == "§6Spider-Benz"){
						$event->getDamager()->sendMessage("§3[§4OPPS+§3]§3 Mounted Spider-Benz.");
						$event->getEntity()->setOwningEntity($event->getDamager());
						$event->getEntity()->setNameTag("§6" . $event->getDamager()->getName() . "'s Spider-Benz");
						$this->toggleFile->set($event->getDamager()->getName() . "-SpiderToggle", true);
						$this->toggleFile->save();
					}else{
						$event->getDamager()->sendMessage("§3[§4OPPS+§3]§3 This Spider-Benz is already taken.");
					}
				}elseif($this->toggleFile->get($event->getDamager()->getName() . "-SpiderToggle") == true){
					if($event->getEntity()->getOwningEntityId() == $event->getDamager()->getId()){
						$event->getDamager()->sendMessage("§3[§4OPPS+§3]§3 Dismounted Spider-Benz.");
						$event->getEntity()->setOwningEntity(null);
						$event->getEntity()->setNameTag("§6Spider-Benz");
						$this->toggleFile->set($event->getDamager()->getName() . "-SpiderToggle", null);
						$this->toggleFile->save();
					}
				}
			}else{
					$event->setCancelled(true);
					$event->getDamager()->sendMessage("§3[§4OPPS+§3]§3 You are not allowed to use this feature.");
				}
			}
		}
		}catch(\Throwable $e){
			return;
		}
	}
}
