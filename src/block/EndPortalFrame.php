<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\block\EndPortalFrame as PMEndPortalFrame;
use skh6075\pmexpansion\block\utils\IBlockState;
use skh6075\pmexpansion\item\EnderEye;
use skh6075\pmexpansion\sound\block\EndPortalFrameFillSound;

class EndPortalFrame extends PMEndPortalFrame implements IBlockState{

	public function getStateSerialize() : ?Closure{
		return static fn(EndPortalFrame $block): BlockStateWriter => BlockStateWriter::create(BlockTypeNames::END_PORTAL_FRAME)
			->writeBool(BlockStateNames::END_PORTAL_EYE_BIT, $block->hasEye())
			->writeLegacyHorizontalFacing($block->getFacing());
	}

	public function getStateDeserialize() : ?Closure{
		return static fn(BlockStateReader $in): EndPortalFrame|Block => ExtraVanillaBlocks::END_PORTAL_FRAME()
			->setEye($in->readBool(BlockStateNames::END_PORTAL_EYE_BIT))
			->setFacing($in->readLegacyHorizontalFacing());
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($item instanceof EnderEye && !$this->hasEye()){
			$this->position->getWorld()->setBlock($this->position, $this->setEye(true));
			$this->position->getWorld()->addSound($this->position, new EndPortalFrameFillSound());

			return true;
		}

		return false;
	}
}