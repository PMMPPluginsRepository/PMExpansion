<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use pocketmine\block\Transparent;
use pocketmine\data\runtime\RuntimeDataReader;
use pocketmine\data\runtime\RuntimeDataWriter;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use skh6075\pmexpansion\block\utils\IBlockState;

abstract class BaseCaveVines extends Transparent implements IBlockState{
	protected const MAX_AGE = 25;

	protected int $age = 0;

	public function getRequiredStateDataBits() : int{ return 5; }

	protected function describeState(RuntimeDataWriter|RuntimeDataReader $w) : void{
		$w->boundedInt(5, 0, self::MAX_AGE, $this->age);
	}

	public function getAge(): int{ return $this->age; }

	public function setAge(int $age): self{
		if($age < 0 || $age > self::MAX_AGE){
			throw new \InvalidArgumentException("Age must be in range 0 ... " . self::MAX_AGE);
		}
		$this->age = $age;
		return $this;
	}

	public function onNearbyBlockChange() : void{
		$up = $this->getSide(Facing::UP);
		if(!$up->isSameType($this) && !$up->isSolid()){
			$this->position->world->useBreakOn($this->position);
		}
	}

	public function getDrops(Item $item) : array{ return []; }
}