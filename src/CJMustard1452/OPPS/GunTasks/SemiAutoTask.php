<?php

namespace CJMustard1452\OPPS\GunTasks;

use CJMustard1452\OPPS\main;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Egg;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\Task;

class SemiAutoTask extends Task{

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
			if($this->ticks == 5){
				$this->plugin->getScheduler()->cancelTask($this->getTaskId());
			}
		}catch(\Throwable $e){
	$this->plugin->getScheduler()->cancelTask($this->getTaskId());
		}
	}
}
