<?php

namespace CJMustard1452\OPPS\GunTasks;

use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Egg;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;

class MachineTask extends Task{

	public $plugin;
	public $ticks = 0;
	private $getPlayer;
	public $toggleFile;

	public function __construct(Plugin $plugin, Player $player){
		$this->plugin = $plugin;
		$this->getPlayer = $player;
		$this->toggleFile = new Config($this->plugin->getDataFolder() . "Toggles", Config::YAML);
	}

	public function onRun(int $currentTick){
		try{
			$this->ticks++;
			$this->plugin->createBullet($this->getPlayer);
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