<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\data\bedrock\block\BlockStateNames;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\item\Item;
use skh6075\pmexpansion\item\ExtraVanillaItems;

class Campfire extends BaseCampfire{
	public function getStateSerialize() : ?Closure{
		return static fn(Campfire $block): BlockStateWriter => BlockStateWriter::create(BlockTypeNames::CAMPFIRE)
			->writeLegacyHorizontalFacing($block->getFacing())
			->writeBool(BlockStateNames::EXTINGUISHED, $block->isExtinguished());
	}

	public function getStateDeserialize() : ?Closure{
		return fn(BlockStateReader $in): Campfire => ExtraVanillaBlocks::CAMPFIRE()
			->setFacing($in->readLegacyHorizontalFacing())
			->setExtinguish($in->readBool(BlockStateNames::EXTINGUISHED));
	}

	public function asItem() : Item{
		return ExtraVanillaItems::CAMPFIRE();
	}
}