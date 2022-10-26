<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Transparent;
use pocketmine\data\bedrock\block\BlockStateNames;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\data\runtime\RuntimeDataReader;
use pocketmine\data\runtime\RuntimeDataWriter;
use pocketmine\item\Hoe;
use pocketmine\item\Item;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\block\utils\IBlockState;

class SculkSensor extends Transparent implements IBlockState{
	use BlockTypeIdTrait;

	private bool $poweredBit = false;

	public function getStateSerialize() : ?Closure{
		return static fn(SculkSensor $block): BlockStateWriter => BlockStateWriter::create(BlockTypeNames::SCULK_SENSOR)
			->writeBool(BlockStateNames::POWERED_BIT, $block->isPoweredBit());
	}

	public function getStateDeserialize() : ?Closure{
		return static fn(BlockStateReader $in): SculkSensor => ExtraVanillaBlocks::SCULK_SENSOR()
			->setPoweredBit($in->readBool(BlockStateNames::POWERED_BIT));
	}

	public function getRequiredStateDataBits() : int{ return 1; }

	protected function describeState(RuntimeDataWriter|RuntimeDataReader $w) : void{
		$w->bool($this->poweredBit);
	}

	public function isPoweredBit(): bool{ return $this->poweredBit; }

	public function setPoweredBit(bool $state): self{
		$this->poweredBit = $state;
		return $this;
	}

	public function getXpDropForTool(Item $item) : int{
		if($item instanceof Hoe){
			return 5;
		}
		return parent::getXpDropForTool($item);
	}
}