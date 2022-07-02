<?php

namespace CJMustard1452\OPPS\Entities;

use pocketmine\entity\Animal;

class Spider extends Animal{

const NETWORK_ID = self::SPIDER;

public $width = 0.9;
public $height = 1.4;

public function getName() : string{
return "Spider";
}
}