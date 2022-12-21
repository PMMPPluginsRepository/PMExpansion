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
use pocketmine\utils\AssumptionFailedError;
use skh6075\pmexpansion\block\utils\IBlockState;
use skh6075\pmexpansion\item\EnderEye;
use skh6075\pmexpansion\world\sound\EndPortalFrameFillSound;
use skh6075\pmexpansion\world\sound\EndPortalFrameSpawnSound;

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

			$this->createEndPortal();
			return true;
		}

		return false;
	}

	private function createEndPortal(): void{
		$center = $this->searchCenter([]);
		$world = $this->position->getWorld();
		for($x = -2; $x <= 2; $x ++){
			for($z = -2; $z <= 2; $z ++){
				if(($x === -2 || $x === 2) && ($z === -2 || $z === 2)){
					continue;
				}
				if($x === -2 || $x === 2 || $z === -2 || $z === 2){
					if(!$this->isEndPortalFrameBlock($world->getBlock($center->add($x, 0, $z), true))){
						return;
					}
				}
			}
		}

		for($x = -1; $x <= 1; $x ++){
			for($z = -1; $z <= 1; $z ++){
				$world->setBlock($center->add($x, 0, $z), ExtraVanillaBlocks::END_PORTAL());
			}
		}

		$world->addSound($center, new EndPortalFrameSpawnSound());
	}

	private function searchCenter(array $visited): Vector3{
		static $blockHash = null;
		if($blockHash === null){
			$blockHash = fn(Block $block): string => $block->getPosition()->getFloorX().':'.$block->getPosition()->getFloorZ();
		}

		$world = $this->position->getWorld();
		for($x = -2; $x <= 2; $x ++){
			if($x === 0){
				continue;
			}

			$block = $world->getBlock($this->position->add($x, 0, 0));
			$iBlock = $world->getBlock($this->position->add($x * 2, 0, 0));
			if($this->isEndPortalFrameBlock($block) && !isset($visited[$hash = $blockHash($block)])){
				$visited[$hash] = true;
				if(($x === -1 || $x === 1) && $this->isEndPortalFrameBlock($iBlock)){
					return $this->searchCenter($visited);
				}

				for($z = -4; $z <= 4; $z ++){
					if($z === 0){
						continue;
					}

					$block = $world->getBlock($this->position->add($x, 0, $z));
					if($this->isEndPortalFrameBlock($block)){
						return $this->position->add($x / 2, 0, $z / 2);
					}
				}
			}
		}

		for($z = -2; $z <= 2; $z ++){
			if($z === 0){
				continue;
			}

			$block = $world->getBlock($this->position->add(0, 0, $z));
			$iBlock = $world->getBlock($this->position->add(0, 0, $z * 2));
			if($this->isEndPortalFrameBlock($block) && !isset($visited[$hash = $blockHash($block)])){
				$visited[$hash] = true;
				if(($z === -1 || $z === 1) && $this->isEndPortalFrameBlock($iBlock)){
					return $this->searchCenter($visited);
				}

				for($x = -4; $x <= 4; $x ++){
					if($x === 0){
						continue;
					}

					$block = $world->getBlock($this->position->add($x, 0, $z));
					if($this->isEndPortalFrameBlock($block)){
						return $this->position->add($x / 2, 0, $z / 2);
					}
				}
			}
		}

		throw new AssumptionFailedError("No center point was found.");
	}

	private function isEndPortalFrameBlock(Block $block, bool $hasEye = false): bool{
		$pos = $block->getPosition();
		$block = $this->position->getWorld()->getBlockAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
		if(!$block instanceof EndPortalFrame){
			return false;
		}
		return !$hasEye || $block->hasEye();
	}
}