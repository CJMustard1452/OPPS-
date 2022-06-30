<?php

declare(strict_types=1);

namespace CJMustard1452\OPPS;

use CJMustard1452\OPPS\Entities\Spider;
use CJMustard1452\OPPS\GunTasks\AK47Task;
use CJMustard1452\OPPS\GunTasks\SemiAutoTask;
use CJMustard1452\OPPS\GunTasks\MachineTask;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Egg;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

	public $toggleFile;

	public function onEnable() :Void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		new Config($this->getDataFolder() . "Toggles", Config::YAML);
		$this->toggleFile = new Config($this->getDataFolder() . "Toggles", Config::YAML);
	}
	public function onInteract(PlayerInteractEvent $event){
		if($event->getPlayer()->getInventory()->getItemInHand()->getId() == 290 || $event->getPlayer()->getInventory()->getItemInHand()->getId() == 294 || $event->getPlayer()->getInventory()->getItemInHand()->getId() == 291 || $event->getPlayer()->getInventory()->getItemInHand()->getId() == 292){
			if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
				$this->toggleFile->reload();
				if(str_contains(implode($event->getPlayer()->getInventory()->getItemInHand()->getLore()), "Glock")){
					if($event->getPlayer()->hasPermission("opps.admin")){
						Entity::registerEntity(Egg::class, true, ['Egg', 'minecraft:egg']);
						$nbt = Entity::createBaseNBT($event->getPlayer()->add($event->getPlayer()->getDirectionVector()->multiply(2))->add(0, 2, 0), $event->getPlayer()->getDirectionVector()->multiply(10), $event->getPlayer()->getYaw(), $event->getPlayer()->getPitch());
						$entity = Entity::createEntity("Egg", $event->getPlayer()->getLevel(), $nbt);
						$entity->spawnTo($event->getPlayer());
					}else{
						$event->getPlayer()->sendMessage("§3[§4OPPS+§3]§3 You are not allowed to have this item.");
						$event->getPlayer()->getInventory()->removeItem($event->getPlayer()->getInventory()->getItemInHand());
					}
				}elseif(str_contains(implode($event->getPlayer()->getInventory()->getItemInHand()->getLore()), "Semi-Automatic")){
					if($event->getPlayer()->hasPermission("opps.admin")){
					$this->getScheduler()->scheduleRepeatingTask(new SemiAutoTask($this, $this->getServer()->getPlayer($event->getPlayer()->getName())), 5);
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
						$this->getScheduler()->scheduleRepeatingTask(new MachineTask($this, $this->getServer()->getPlayer($event->getPlayer()->getName())), 1);
						$this->toggleFile->set($event->getPlayer()->getName(), true);
						$this->toggleFile->save();
					}
					}else{
						$event->getPlayer()->sendMessage("§3[§4OPPS+§3]§3 You are not allowed to have this item.");
						$event->getPlayer()->getInventory()->removeItem($event->getPlayer()->getInventory()->getItemInHand());
					}
				}elseif(str_contains(implode($event->getPlayer()->getInventory()->getItemInHand()->getLore()), "AK-47")){
					if($event->getPlayer()->hasPermission("opps.admin")){
					$this->getScheduler()->scheduleRepeatingTask(new AK47Task($this, $this->getServer()->getPlayer($event->getPlayer()->getName())), 1);
				}else{
					$event->getPlayer()->sendMessage("§3[§4OPPS+§3]§3 You are not allowed to have this item.");
					$event->getPlayer()->getInventory()->removeItem($event->getPlayer()->getInventory()->getItemInHand());
					}
				}
			}
		}
	}
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if($sender instanceof Player){
			if(isset($args[0]) && strtolower($args[0]) == "glock"){
				$sender->sendMessage("§3[§4OPPS+§3]§3 You have been given a §6Glock 9§3.");
				$sender->getInventory()->addItem(Item::get(290)->setLore(["Glock"])->setCustomName("§6Glock 9"));
			}elseif(isset($args[0]) && strtolower($args[0]) == "semi"){
				$sender->sendMessage("§3[§4OPPS+§3]§3 You have been given a §6Semi-Automatic§3.");
				$sender->getInventory()->addItem(Item::get(291)->setLore(["Semi-Automatic"])->setCustomName("§6Semi-Automatic"));
			}elseif(isset($args[0]) && strtolower($args[0]) == "machine"){
				$sender->sendMessage("§3[§4OPPS+§3]§3 You have been given a §6Machine-Gun§3.");
				$sender->getInventory()->addItem(Item::get(292)->setLore(["Machine-Gun"])->setCustomName("§6Machine-Gun"));
			}elseif(isset($args[0]) && strtolower($args[0]) == "ak47"){
				$sender->sendMessage("§3[§4OPPS+§3]§3 You have been given a §6AK-47§3.");
				$sender->getInventory()->addItem(Item::get(294)->setLore(["AK-47"])->setCustomName("§6AK-47"));
			}elseif(isset($args[0]) && strtolower($args[0]) == "benz"){
				$sender->sendMessage("§3[§4OPPS+§3]§3 You have spawned §6Rideable Spider§3.");
				Entity::registerEntity(Spider::class);
				$nbt = Entity::createBaseNBT($sender->getPosition(), null, $sender->getYaw(), $sender->getPitch());
				$entity = Entity::createEntity("Spider", $sender->getLevel(), $nbt);
				$entity->spawnToAll();
				$entity->setNameTag("§3[§6Spider-Benz§3]");
			}else{
				$sender->sendMessage("§3[§4OPPS+§3]§3 /opps (Glock | Semi | Machine | AK47)");
			}
		}else{
			$sender->sendMessage("You can only run this command in game.");
		}
		return true;
	}
	public function onDisable(){
		unlink($this->getDataFolder() . "Toggles");
		return true;
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
