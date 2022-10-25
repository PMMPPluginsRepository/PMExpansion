<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Opaque;
use pocketmine\item\Hoe;
use pocketmine\item\Item;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\block\utils\IBlockState;

class Sculk extends Opaque implements IBlockState{
	use BlockTypeIdTrait;

	public function getStateSerialize() : ?Closure{ return null; }

	public function getStateDeserialize() : ?Closure{ return null; }

	public function getXpDropForTool(Item $item) : int{
		if($item instanceof Hoe){
			return 5;
		}
		return parent::getXpDropForTool($item);
	}
}