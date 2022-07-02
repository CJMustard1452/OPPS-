<?php

namespace CJMustard1452\OPPS;

use CJMustard1452\OPPS\GunTasks\AK47Task;
use CJMustard1452\OPPS\GunTasks\MachineTask;
use CJMustard1452\OPPS\GunTasks\SemiAutoTask;
use pocketmine\entity\projectile\Egg;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Hoe;
use pocketmine\level\Explosion;
use pocketmine\Server;
use pocketmine\utils\Config;

class EventListener implements Listener{

/** @var Server */
public $getServer;
public $getPlugin;
public $toggleFile;

public function __construct(){
$this->getServer = Server::getInstance();
$this->getPlugin = $this->getServer->getPluginManager()->getPlugin("OPPS");
$this->toggleFile = new Config($this->getPlugin->getDataFolder() . "Toggles", Config::YAML);
}

	public function onInteract(PlayerInteractEvent $event){
		if($event->getPlayer()->getInventory()->getItemInHand() instanceof Hoe && $event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
			$this->toggleFile->reload();
			switch(implode($event->getPlayer()->getInventory()->getItemInHand()->getLore())){
				case "Glock":
					if($this->getPlugin->checkPermission($event->getPlayer()) == 2){
						$this->getPlugin->createBullet($event->getPlayer());
					}
					break;
				case "Semi-Automatic":
					if($this->getPlugin->checkPermission($event->getPlayer()) == 2){
						$this->getPlugin->getScheduler()->scheduleRepeatingTask(new SemiAutoTask($this->getPlugin, $this->getServer->getPlayer($event->getPlayer()->getName())), 5);
					}
					break;
				case "RPG":
					if($this->getPlugin->checkPermission($event->getPlayer()) == 2){
						$this->getPlugin->createExplodingBullet($event->getPlayer());
					}
					break;
				case "Machine-Gun":
					if($this->getPlugin->checkPermission($event->getPlayer()) == 2){
						if($this->toggleFile->get($event->getPlayer()->getName()) == true){
							$event->getPlayer()->sendMessage("§3[§4OPPS+§3]§3 Your Machine-Gun has been toggled off.");
							$this->toggleFile->set($event->getPlayer()->getName(), null);
							$this->toggleFile->save();
						}else{
							$event->getPlayer()->sendMessage("§3[§4OPPS+§3]§3 Your Machine-Gun has been toggled on.");
							$this->getPlugin->getScheduler()->scheduleRepeatingTask(new MachineTask($this->getPlugin, $this->getServer->getPlayer($event->getPlayer()->getName())), 1);
							$this->toggleFile->set($event->getPlayer()->getName(), true);
							$this->toggleFile->save();
						}
					}
					break;
				case "AK-47":
					if($this->getPlugin->checkPermission($event->getPlayer()) == 2){
						$this->getPlugin->getScheduler()->scheduleRepeatingTask(new AK47Task($this->getPlugin, $this->getServer->getPlayer($event->getPlayer()->getName())), 1);
					}
					break;
			}
		}
	}

	public function onColide(ProjectileHitEvent $event){
		if($event->getEntity() instanceof Egg){
			if($event->getEntity()->getNameTag() == "Exploding Egg"){
				$explosion = new Explosion($event->getEntity()->getPosition(), 6, $this);
				$explosion->explodeB();
			}
		}
	}
	public function onDamage(EntityDamageEvent $event){
		if($event->getCause() == 2){
			if($event->getDamager() instanceof Egg && $event->getDamager()->getOwningEntityId() == $event->getEntity()->getId() || $event->getDamager() == $event->getEntity()){
				$event->setCancelled(true);
			}
			}elseif($event->getCause() == 9){
				$event->setCancelled(true);
			}
	}
	public function onLogout(PlayerQuitEvent $event){
		$this->toggleFile->reload();
		if($this->toggleFile->get($event->getPlayer()->getName()) == true){
			$this->toggleFile->set($event->getPlayer()->getName(), null);
			$this->toggleFile->save();
		}
	}
	public function onDeath(PlayerDeathEvent $event){
		$this->toggleFile->reload();
		if($this->toggleFile->get($event->getPlayer()->getName()) == true){
			$event->getPlayer()->sendMessage("§3[§4OPPS+§3]§3 Your Machine-Gun has been toggled off.");
			$this->toggleFile->set($event->getPlayer()->getName(), null);
			$this->toggleFile->save();
		}
	}
	public function onSlotChange(PlayerItemHeldEvent $event){
		$this->toggleFile->reload();
		if($this->toggleFile->get($event->getPlayer()->getName()) == true){
			$event->getPlayer()->sendMessage("§3[§4OPPS+§3]§3 Your Machine-Gun has been toggled off.");
			$this->toggleFile->set($event->getPlayer()->getName(), null);
			$this->toggleFile->save();
		}
	}
}
