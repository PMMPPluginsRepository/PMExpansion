<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\item;

use pocketmine\block\Block;
use pocketmine\item\Food;
use skh6075\pmexpansion\block\ExtraVanillaBlocks;
use skh6075\pmexpansion\item\utils\ItemTypeIdTrait;

class GlowBerries extends Food{
	use ItemTypeIdTrait;

	public function getFoodRestore() : int{ return 2; }

	public function getSaturationRestore() : float{ return 0.6; }

	public function getBlock(?int $clickedFace = null) : Block{
		return ExtraVanillaBlocks::CAVE_VINES();
	}
}