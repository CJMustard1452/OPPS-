<?php

namespace CJMustard1452\OPPS\Entities\Spider;

use CJMustard1452\OPPS\Entities\Spider\Spider;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\Task;

class ClearSpidersTask extends Task{

	public $plugin;
	public $ticks = 0;

	public function __construct(Plugin $plugin){
		$this->plugin = $plugin;
	}

	public function onRun(int $currentTick){
		$this->ticks++;
		if($this->ticks == 300){
			foreach($this->plugin->getServer()->getLevels() as $level){
				foreach($level->getEntities() as $entity){
					if($entity instanceof Spider){
						if($entity->getOwningEntityId() == null){
							$entity->flagForDespawn();
						}
					}
				}
			}
			$this->ticks = 0;
		}
	}
}
