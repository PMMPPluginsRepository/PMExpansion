<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Opaque;
use pocketmine\entity\Entity;
use pocketmine\math\AxisAlignedBB;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\block\utils\IBlockState;

class Honey extends Opaque implements IBlockState{
	use BlockTypeIdTrait;

	public function getStateSerialize() : ?Closure{ return null; }

	public function getStateDeserialize() : ?Closure{ return null; }

	public function hasEntityCollision() : bool{ return true; }

	protected function recalculateCollisionBoxes() : array{
		return [new AxisAlignedBB(0.1, 0, 0.1, 0.9, 1, 0.9)];
	}

	public function onEntityLand(Entity $entity) : ?float{
		$entity->fallDistance *= 0.2;
		return null;
	}
}