<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\item;

use pocketmine\block\Block;
use pocketmine\item\Item;
use skh6075\pmexpansion\block\ExtraVanillaBlocks;
use skh6075\pmexpansion\item\utils\ItemTypeIdTrait;

class Campfire extends Item{
	use ItemTypeIdTrait;

	public function getBlock(?int $clickedFace = null) : Block{
		return ExtraVanillaBlocks::CAMPFIRE();
	}
}