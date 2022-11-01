<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Block;
use pocketmine\block\Transparent;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Fertilizer;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\Random;
use pocketmine\world\BlockTransaction;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\block\utils\IBlockState;
use skh6075\pmexpansion\world\generator\object\AzaleaTree;

class Azalea extends Transparent implements IBlockState{
	use BlockTypeIdTrait;

	public function getStateSerialize() : ?Closure{ return null; }

	public function getStateDeserialize() : ?Closure{ return null; }

	public function onNearbyBlockChange(): void{
		if($this->getSide(Facing::DOWN)->isSameType(VanillaBlocks::AIR())){
			$this->position->getWorld()->useBreakOn($this->position);
		}
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if(!$this->getSide(Facing::DOWN)->isTransparent()){
			return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
		}
		return false;
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($item instanceof Fertilizer){
			$this->grow();

			return true;
		}

		return false;
	}

	private function grow(): void{
		(new AzaleaTree())->generate($this->position->getWorld(), new Random(), $this->position);
	}
}