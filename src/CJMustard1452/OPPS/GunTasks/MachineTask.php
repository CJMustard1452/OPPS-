<?php

namespace CJMustard1452\OPPS\GunTasks;

use CJMustard1452\OPPS\main;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Egg;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

class MachineTask extends Task{

	public $plugin;
	public $ticks = 0;
	private $getPlayer;
	public $toggleFile;

	public function __construct(Main $plugin, Player $player){
		$this->plugin = $plugin;
		$this->getPlayer = $player;
		$this->toggleFile = new Config($this->plugin->getDataFolder() . "Toggles", Config::YAML);
	}

	public function onRun(int $currentTick){
		try{
			$this->ticks++;
			Entity::registerEntity(Egg::class, true, ['Egg', 'minecraft:egg']);
			$nbt = Entity::createBaseNBT($this->getPlayer->add($this->getPlayer->getDirectionVector()->multiply(2))->add(0, 2, 0), $this->getPlayer->getDirectionVector()->multiply(10), $this->getPlayer->getYaw(), $this->getPlayer->getPitch());
			$entity = Entity::createEntity("Egg", $this->getPlayer->getLevel(), $nbt);
			$entity->spawnTo($this->getPlayer);
			if($this->ticks == 20){
				$this->toggleFile->reload();
				if($this->toggleFile->get($this->getPlayer->getName()) == false){
					$this->plugin->getScheduler()->cancelTask($this->getTaskId());
				}
				$this->ticks = 0;
			}
		}catch(\Throwable $e){
			$this->plugin->getScheduler()->cancelTask($this->getTaskId());
		}
	}
}