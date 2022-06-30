<?php

declare(strict_types=1);

namespace CJMustard1452\OPPS;

use CJMustard1452\OPPS\Commands;
use CJMustard1452\OPPS\Entities\Spider;
use CJMustard1452\OPPS\EventListener;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Egg;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

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
		Entity::registerEntity(Egg::class, true, ['Egg', 'minecraft:egg']);
		$nbt = Entity::createBaseNBT($player->add($player->getDirectionVector()->multiply(1.5))->add(0, 2, 0), $player->getDirectionVector()->multiply(10), $player->getYaw(), $player->getPitch());
		$entity = Entity::createEntity("Egg", $player->getLevel(), $nbt);
		$entity->spawnTo($player);
		$entity->setNameTag($player->getName());
	}
	public function createSpider(Player $player){
		Entity::registerEntity(Spider::class);
		$nbt = Entity::createBaseNBT($player->getPosition(), null, $player->getYaw(), $player->getPitch());
		$entity = Entity::createEntity("Spider", $player->getLevel(), $nbt);
		$entity->spawnToAll();
		$entity->setNameTag("§6" . $player->getName() . "'s Spider-Benz");
		$entity->setScale(1.5);

	}
}
