<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Opaque;
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

class SculkCatalyst extends Opaque implements IBlockState{
	use BlockTypeIdTrait;

	private bool $bloom = false;

	public function getStateSerialize() : ?Closure{
		return static fn(SculkCatalyst $block): BlockStateWriter => BlockStateWriter::create(BlockTypeNames::SCULK_CATALYST)
			->writeBool(BlockStateNames::BLOOM, $block->isBloom());
	}

	public function getStateDeserialize() : ?Closure{
		return static fn(BlockStateReader $in): SculkCatalyst => ExtraVanillaBlocks::SCULK_CATALYST()
			->setBloom($in->readBool(BlockStateNames::BLOOM));
	}

	public function getRequiredStateDataBits() : int{ return 1; }

	protected function describeState(RuntimeDataReader|RuntimeDataWriter $w) : void{
		$w->bool($this->bloom);
	}

	public function isBloom(): bool{ return $this->bloom; }

	public function setBloom(bool $state): self{
		$this->bloom = $state;
		return $this;
	}

	public function getXpDropForTool(Item $item) : int{
		if($item instanceof Hoe){
			return 5;
		}
		return parent::getXpDropForTool($item);
	}

	public function getLightLevel() : int{ return 6; }
}