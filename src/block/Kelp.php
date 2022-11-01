<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Block;
use pocketmine\block\Transparent;
use pocketmine\data\bedrock\block\BlockStateNames;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\data\runtime\RuntimeDataReader;
use pocketmine\data\runtime\RuntimeDataWriter;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\block\utils\IBlockState;
use skh6075\pmexpansion\item\ExtraVanillaItems;

class Kelp extends Transparent implements IBlockState{
	use BlockTypeIdTrait;

	protected const MAX_AGE = 25;

	protected int $age = 0;

	public function getStateSerialize() : ?Closure{
		return static fn(Kelp $block) : BlockStateWriter => BlockStateWriter::create(BlockTypeNames::KELP)
			->writeInt(BlockStateNames::KELP_AGE, $block->getAge());
	}

	public function getStateDeserialize() : ?Closure{
		return static fn(BlockStateReader $in) : Kelp => ExtraVanillaBlocks::KELP()
			->setAge($in->readBoundedInt(BlockStateNames::KELP_AGE, 0, self::MAX_AGE));
	}

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

	protected function recalculateCollisionBoxes() : array{ return []; }

	public function getDrops(Item $item) : array{
		return [ExtraVanillaItems::KELP()];
	}

	public function onNearbyBlockChange() : void{
		$world = $this->position->getWorld();
		if(!$this->canBeSupportedBy($this->getSide(Facing::DOWN))){
			$world->useBreakOn($this->position);
		}else{
			foreach(Facing::HORIZONTAL as $side){
				$b = $this->getSide($side);
				if($b->isSolid()){
					$world->useBreakOn($this->position);
					break;
				}
			}
		}
	}

	private function canBeSupportedBy(Block $block) : bool{
		return $block->getSupportType(Facing::UP)->hasCenterSupport();
	}
}