<?php

declare(strict_types=1);

namespace CJMustard1452\OPPS;

use CJMustard1452\OPPS\Commands;
use CJMustard1452\OPPS\Entities\Spider;
use CJMustard1452\OPPS\EventListener;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Egg;
use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

	public $spawnEgg;

	public function onEnable() :Void{
		$this->getServer()->getPluginManager()->registerEvents(new Commands(), $this);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		new Config($this->getDataFolder() . "Toggles", Config::YAML);
	}
	public function onDisable(){
		unlink($this->getDataFolder() . "Toggles");
	}
	public function createBullet(Player $player){
		$nbt = Entity::createBaseNBT($player->add($player->getDirectionVector()->multiply(1.5))->add(0, 2, 0), $player->getDirectionVector()->multiply(10), $player->getYaw(), $player->getPitch());
		$entity = Entity::createEntity("Egg", $player->getLevel(), $nbt);
		$entity->spawnTo($player);
		$entity->setNameTag($player->getName());
	}
	public function createExplodingBullet(Player $player){
		$nbt = Entity::createBaseNBT($player->add($player->getDirectionVector()->multiply(1.5))->add(0, 2, 0), $player->getDirectionVector()->multiply(10), $player->getYaw(), $player->getPitch());
		$entity = Entity::createEntity("Egg", $player->getLevel(), $nbt);
		$entity->spawnTo($player);
		$entity->setNameTag("Exploding Egg");
	}
	public function createSpider(Player $player){
		Entity::registerEntity(Spider::class);
		$nbt = Entity::createBaseNBT($player->getPosition(), null, $player->getYaw(), $player->getPitch());
		$entity = Entity::createEntity("Spider", $player->getLevel(), $nbt);
		$entity->spawnToAll();
		$entity->setNameTag("§6" . $player->getName() . "'s Spider-Benz");
		$entity->setScale(1.5);
	}
	public function checkPermission(Player $player){
		if(!$player->hasPermission("opps.admin")){
			$player->getInventory()->removeItem($player->getInventory()->getItemInHand());
			$player->sendMessage("§3[§4OPPS+§3]§3 You are not allowed to have this item.");
		}else{
			return(2);
		}
	}
}
