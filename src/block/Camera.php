<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Block;
use pocketmine\block\Opaque;
use pocketmine\block\utils\FacesOppositePlacingPlayerTrait;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\block\utils\IBlockState;

class Camera extends Opaque implements IBlockState{
	use BlockTypeIdTrait;
	use FacesOppositePlacingPlayerTrait;
	use HorizontalFacingTrait;

	public function getStateSerialize() : ?Closure{
		return static fn(Camera $block): BlockStateWriter => BlockStateWriter::create(BlockTypeNames::CAMERA)
			->writeHorizontalFacing($block->getFacing());
	}

	public function getStateDeserialize() : ?Closure{
		return static fn(BlockStateReader $in): Camera|Block => ExtraVanillaBlocks::CAMERA()
			->setFacing($in->readHorizontalFacing());
	}
}