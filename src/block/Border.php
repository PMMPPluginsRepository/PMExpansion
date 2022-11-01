<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Wall;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\block\convert\BlockStateDeserializerHelper;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateSerializerHelper;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\block\utils\IBlockState;

class Border extends Wall implements IBlockState{
	use BlockTypeIdTrait;

	public function getStateSerialize() : ?Closure{
		return static fn(Border $block) : BlockStateWriter => BlockStateSerializerHelper::encodeWall($block, new BlockStateWriter(BlockTypeNames::BORDER_BLOCK));
	}

	public function getStateDeserialize() : ?Closure{
		return static fn(BlockStateReader $in) : Border|Wall => BlockStateDeserializerHelper::decodeWall(ExtraVanillaBlocks::BORDER(), $in);
	}
}