<?php

namespace CJMustard1452\OPPS\GunTasks;

use CJMustard1452\OPPS\main;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Egg;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class SemiAutoTask extends Task{

	public $plugin;
	public $ticks = 0;
	private $getPlayer;

	public function __construct(Main $plugin, Player $player){
		$this->plugin = $plugin;
		$this->getPlayer = $player;
	}

	public function onRun(int $currentTick){
		try{
			$this->ticks++;
			Entity::registerEntity(Egg::class, true, ['Egg', 'minecraft:egg']);
			$nbt = Entity::createBaseNBT($this->getPlayer->add($this->getPlayer->getDirectionVector()->multiply(1.5))->add(0, 2, 0), $this->getPlayer->getDirectionVector()->multiply(10), $this->getPlayer->getYaw(), $this->getPlayer->getPitch());
			$entity = Entity::createEntity("Egg", $this->getPlayer->getLevel(), $nbt);
			$entity->spawnTo($this->getPlayer);
			$entity->setNameTag($this->getPlayer->getName());
			if($this->ticks == 5){
				$this->plugin->getScheduler()->cancelTask($this->getTaskId());
			}
		}catch(\Throwable $e){
	$this->plugin->getScheduler()->cancelTask($this->getTaskId());
		}
	}
}