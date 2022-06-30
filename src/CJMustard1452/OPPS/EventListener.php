<?php

namespace CJMustard1452\OPPS;

use CJMustard1452\OPPS\GunTasks\AK47Task;
use CJMustard1452\OPPS\GunTasks\MachineTask;
use CJMustard1452\OPPS\GunTasks\SemiAutoTask;
use pocketmine\entity\projectile\Egg;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerQuitEvent;
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
		if($event->getPlayer()->getInventory()->getItemInHand()->getId() == 290 || $event->getPlayer()->getInventory()->getItemInHand()->getId() == 294 || $event->getPlayer()->getInventory()->getItemInHand()->getId() == 291 || $event->getPlayer()->getInventory()->getItemInHand()->getId() == 292){
			if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
				$this->toggleFile->reload();
				if(str_contains(implode($event->getPlayer()->getInventory()->getItemInHand()->getLore()), "Glock")){
					if($event->getPlayer()->hasPermission("opps.admin")){
						$this->getPlugin->createBullet($event->getPlayer());
					}else{
						$event->getPlayer()->sendMessage("§3[§4OPPS+§3]§3 You are not allowed to have this item.");
						$event->getPlayer()->getInventory()->removeItem($event->getPlayer()->getInventory()->getItemInHand());
					}
				}elseif(str_contains(implode($event->getPlayer()->getInventory()->getItemInHand()->getLore()), "Semi-Automatic")){
					if($event->getPlayer()->hasPermission("opps.admin")){
						$this->getPlugin->getScheduler()->scheduleRepeatingTask(new SemiAutoTask($this->getPlugin, $this->getServer->getPlayer($event->getPlayer()->getName())), 5);
					}else{
						$event->getPlayer()->sendMessage("§3[§4OPPS+§3]§3 You are not allowed to have this item.");
						$event->getPlayer()->getInventory()->removeItem($event->getPlayer()->getInventory()->getItemInHand());
					}
				}elseif(str_contains(implode($event->getPlayer()->getInventory()->getItemInHand()->getLore()), "Machine-Gun")){
					if($event->getPlayer()->hasPermission("opps.admin")){
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
					}else{
						$event->getPlayer()->sendMessage("§3[§4OPPS+§3]§3 You are not allowed to have this item.");
						$event->getPlayer()->getInventory()->removeItem($event->getPlayer()->getInventory()->getItemInHand());
					}
				}elseif(str_contains(implode($event->getPlayer()->getInventory()->getItemInHand()->getLore()), "AK-47")){
					if($event->getPlayer()->hasPermission("opps.admin")){
						$this->getPlugin->getScheduler()->scheduleRepeatingTask(new AK47Task($this->getPlugin, $this->getServer->getPlayer($event->getPlayer()->getName())), 1);
					}else{
						$event->getPlayer()->sendMessage("§3[§4OPPS+§3]§3 You are not allowed to have this item.");
						$event->getPlayer()->getInventory()->removeItem($event->getPlayer()->getInventory()->getItemInHand());
					}
				}
			}
		}
	}
	public function onDamage(EntityDamageEvent $event){
		if($event->getCause() == 2){
			if($event->getDamager() instanceof Egg && $event->getDamager()->getNameTag() == $event->getEntity()->getName()){
				$event->setCancelled(true);
			}
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
