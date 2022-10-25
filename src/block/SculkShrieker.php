<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Opaque;
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

class SculkShrieker extends Opaque implements IBlockState{
	use BlockTypeIdTrait;

	private bool $active = false;
	private bool $canSummon = false;

	public function getStateSerialize() : ?Closure{
		return static fn(SculkShrieker $block): BlockStateWriter => BlockStateWriter::create(BlockTypeNames::SCULK_SHRIEKER)
			->writeBool(BlockStateNames::ACTIVE, $block->isActive())
			->writeBool(BlockStateNames::CAN_SUMMON, $block->canSummon());
	}

	public function getStateDeserialize() : ?Closure{
		return static fn(BlockStateReader $in): SculkShrieker => ExtraVanillaBlocks::SCULK_SHRIEKER()
			->setActive($in->readBool(BlockStateNames::ACTIVE))
			->setSummon($in->readBool(BlockStateNames::CAN_SUMMON));
	}

	public function getRequiredStateDataBits() : int{ return 2; }

	protected function describeState(RuntimeDataWriter|RuntimeDataReader $w) : void{
		$w->bool($this->active);
		$w->bool($this->canSummon);
	}

	public function isActive(): bool{ return $this->active; }

	public function setActive(bool $state): self{
		$this->active = $state;
		return $this;
	}

	public function canSummon(): bool{
		return $this->canSummon;
	}

	public function setSummon(bool $state): self{
		$this->canSummon = $state;
		return $this;
	}

	public function getXpDropForTool(Item $item) : int{
		if($item instanceof Hoe){
			return 5;
		}
		return parent::getXpDropForTool($item);
	}
}