<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Block;
use pocketmine\block\Transparent;
use pocketmine\block\utils\PillarRotationTrait;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\block\utils\IBlockState;
use skh6075\pmexpansion\item\ExtraVanillaItems;

class Chain extends Transparent implements IBlockState{
	use BlockTypeIdTrait;
	use PillarRotationTrait;

	public function getStateSerialize() : ?Closure{
		return static fn(Chain $block): BlockStateWriter => BlockStateWriter::create(BlockTypeNames::CHAIN)
			->writePillarAxis($block->getAxis());
	}

	public function getStateDeserialize() : ?Closure{
		return static fn(BlockStateReader $in): Chain|Block => ExtraVanillaBlocks::CHAIN()
			->setAxis($in->readPillarAxis());
	}

	protected function recalculateCollisionBoxes() : array{
		return [AxisAlignedBB::one()->trim($this->axis << 1, 0.3)->trim(Facing::opposite($this->axis << 1), 0.3)];
	}

	public function asItem() : Item{
		return ExtraVanillaItems::CHAIN();
	}
}