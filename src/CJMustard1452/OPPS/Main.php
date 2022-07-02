<?php

declare(strict_types=1);

namespace CJMustard1452\OPPS;

use CJMustard1452\OPPS\Entities\Spider;
use CJMustard1452\OPPS\Entities\SpiderAI;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

	public function onEnable() :Void{
		$this->getServer()->getPluginManager()->registerEvents(new Commands(), $this);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
		$this->getServer()->getPluginManager()->registerEvents(new SpiderAI(), $this);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		new Config($this->getDataFolder() . "Toggles", Config::YAML);
	}
	public function onDisable(){
		unlink($this->getDataFolder() . "Toggles");
	}
	public function createBullet(Player $player){
		$nbt = Entity::createBaseNBT($player->add($player->getDirectionVector()->multiply(2))->add(0, 2, 0), $player->getDirectionVector()->multiply(10), $player->getYaw(), $player->getPitch());
		$entity = Entity::createEntity("Egg", $player->getLevel(), $nbt);
		$entity->setOwningEntity($player);
		$entity->setScale($player->getScale() * 1);
		$entity->spawnTo($player);
	}
	public function createExplodingBullet(Player $player){
		$nbt = Entity::createBaseNBT($player->add($player->getDirectionVector()->multiply(1.5))->add(0, 2, 0), $player->getDirectionVector()->multiply(10), $player->getYaw(), $player->getPitch());
		$entity = Entity::createEntity("Egg", $player->getLevel(), $nbt);
		$entity->setOwningEntity($player);
		$entity->spawnTo($player);
		$entity->setScale($player->getScale() * 1);
		$entity->setNameTag("Exploding Egg");
	}
	public function createSpider(Player $player){
		Entity::registerEntity(Spider::class);
		$nbt = Entity::createBaseNBT($player->getPosition(), null, $player->getYaw(), $player->getPitch());
		$entity = Entity::createEntity("Spider", $player->getLevel(), $nbt);
		$entity->spawnToAll();
		$entity->setNameTag("§6Spider-Benz");
		$entity->setMaxHealth(99999999);
		$entity->setHealth(9999999);
		$entity->setScale($player->getScale() * 1.5);
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
