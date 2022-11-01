<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\BlockToolType;
use pocketmine\block\Transparent;
use pocketmine\data\bedrock\block\BlockStateNames;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\data\runtime\RuntimeDataReader;
use pocketmine\data\runtime\RuntimeDataWriter;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\block\utils\IBlockState;

class AzaleaLeavesFlowered extends Transparent implements IBlockState{
	use BlockTypeIdTrait;

	private bool $persistentBit = false;
	private bool $updateBit = false;

	public function getStateSerialize() : ?Closure{
		return static fn(AzaleaLeavesFlowered $block): BlockStateWriter => BlockStateWriter::create(BlockTypeNames::AZALEA_LEAVES_FLOWERED)
			->writeBool(BlockStateNames::PERSISTENT_BIT, $block->isPersistentBit())
			->writeBool(BlockStateNames::UPDATE_BIT, $block->isUpdateBit());
	}

	public function getStateDeserialize() : ?Closure{
		return static fn(BlockStateReader $in) : AzaleaLeavesFlowered => ExtraVanillaBlocks::AZALEA_LEAVES_FLOWERED()
			->setPersistentBit($in->readBool(BlockStateNames::PERSISTENT_BIT))
			->setUpdateBit($in->readBool(BlockStateNames::UPDATE_BIT));
	}

	public function getRequiredStateDataBits() : int{ return 2; }

	protected function describeState(RuntimeDataWriter|RuntimeDataReader $w) : void{
		$w->bool($this->persistentBit);
		$w->bool($this->updateBit);
	}

	public function isPersistentBit(): bool{ return $this->persistentBit; }

	public function setPersistentBit(bool $state): self{
		$this->persistentBit = $state;
		return $this;
	}

	public function isUpdateBit(): bool{ return $this->updateBit; }

	public function setUpdateBit(bool $state): self{
		$this->updateBit = $state;
		return $this;
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		if(($item->getBlockToolType() & BlockToolType::SHEARS) !== 0){
			return parent::getDropsForCompatibleTool($item);
		}

		$drops = [];
		if(random_int(1, 256) === 1){
			$drops[] = ExtraVanillaBlocks::AZALEA()->asItem();
		}
		if(random_int(1, 50) === 1){
			$drops[] = VanillaItems::STICK();
		}

		return $drops;
	}
}