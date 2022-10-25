<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\item;

use pocketmine\entity\Location;
use pocketmine\entity\projectile\Throwable;
use pocketmine\item\ProjectileItem;
use pocketmine\player\Player;
use skh6075\pmexpansion\item\utils\ItemTypeIdTrait;
use skh6075\pmexpansion\entity\projectile\IceBomb as IceBombEntity;

class IceBomb extends ProjectileItem{
	use ItemTypeIdTrait;

	public function getThrowForce() : float{
		return 1.5;
	}

	protected function createEntity(Location $location, Player $thrower) : Throwable{
		return new IceBombEntity($location, $thrower);
	}

	public function getCooldownTicks() : int{
		return 10;
	}
}