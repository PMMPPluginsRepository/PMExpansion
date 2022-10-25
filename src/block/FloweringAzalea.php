<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Transparent;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\block\utils\IBlockState;

final class FloweringAzalea extends Transparent implements IBlockState{
	use BlockTypeIdTrait;

	public function getStateSerialize() : ?Closure{ return null; }

	public function getStateDeserialize() : ?Closure{ return null; }
}