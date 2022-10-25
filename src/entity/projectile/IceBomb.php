<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\entity\projectile;

use pocketmine\block\VanillaBlocks;
use pocketmine\block\Water;
use pocketmine\block\WaterLily;
use pocketmine\entity\projectile\Throwable;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\sound\BlockBreakSound;

class IceBomb extends Throwable{
	public static function getNetworkTypeId() : string{ return EntityIds::ICE_BOMB; }

	protected function entityBaseTick(int $tickDiff = 1) : bool{
		if($this->closed){
			return false;
		}

		$hasUpdate = parent::entityBaseTick($tickDiff);
		if($this->isUnderwater()){
			$this->onNearbyWaterFreeze();
			$this->flagForDespawn();
			$hasUpdate = true;
		}
		return $hasUpdate;
	}

	private function onNearbyWaterFreeze() : void{
		$center = $this->getPosition();

		for($x = -1; $x <= 1; ++$x){
			for($z = -1; $z <= 1; ++$z){
				$blockPos = $center->add($x, 0, $z);
				$block = $this->getWorld()->getBlock($blockPos);
				if($block instanceof Water || $block instanceof WaterLily){
					$this->getWorld()->setBlock($blockPos, VanillaBlocks::ICE());
				}
			}
		}
		$center->getWorld()->addSound($center, new BlockBreakSound(VanillaBlocks::ICE()));
	}
}