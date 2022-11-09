<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use pocketmine\block\Transparent;
use pocketmine\data\runtime\RuntimeDataReader;
use pocketmine\data\runtime\RuntimeDataWriter;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use skh6075\pmexpansion\block\utils\BlockAgeTrait;
use skh6075\pmexpansion\block\utils\IBlockState;

abstract class BaseCaveVines extends Transparent implements IBlockState{
	use BlockAgeTrait;

	public function getRequiredStateDataBits() : int{ return 5; }

	protected function getMaxAge() : int{ return 25; }

	protected function describeState(RuntimeDataWriter|RuntimeDataReader $w) : void{
		$w->boundedInt(5, 0, $this->getMaxAge(), $this->age);
	}

	public function onNearbyBlockChange() : void{
		$up = $this->getSide(Facing::UP);
		if(!$up->isSameType($this) && !$up->isSolid()){
			$this->position->world->useBreakOn($this->position);
		}
	}

	public function getDrops(Item $item) : array{ return []; }
}