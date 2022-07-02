<?php

namespace CJMustard1452\OPPS\GunTasks;

use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Egg;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\Task;

class AK47Task extends Task{

	public $plugin;
	public $ticks = 0;
	private $getPlayer;

	public function __construct(Plugin $plugin, Player $player){
		$this->plugin = $plugin;
		$this->getPlayer = $player;
	}

	public function onRun(int $currentTick){
		try{
			$this->ticks++;
			$this->plugin->createBullet($this->getPlayer);
			if($this->ticks == 7){
				$this->plugin->getScheduler()->cancelTask($this->getTaskId());
			}
		}catch(\Throwable $e){
			$this->plugin->getScheduler()->cancelTask($this->getTaskId());
		}
	}
}
