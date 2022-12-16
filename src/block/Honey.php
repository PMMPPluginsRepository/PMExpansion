<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Opaque;
use pocketmine\entity\Entity;
use pocketmine\math\AxisAlignedBB;
use pocketmine\player\Player;
use pocketmine\utils\Random;
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

	public function onEntityInside(Entity $entity) : bool{
		static $random = null;
		if($random === null){
			$random = new Random();
		}

		if(!$entity->isOnGround() && $entity->getMotion()->y <= 0.08 && (!$entity instanceof Player || $entity->hasFiniteResources())){
			$deltaX = abs($this->position->x + 0.5 - $entity->getPosition()->x);
			$deltaZ = abs($this->position->z + 0.5 - $entity->getPosition()->z);
			$width = 0.4375 + ($entity->getSize()->getWidth() / 2.0);
			if($deltaX + 1.0 - 3.0 > $width || $deltaZ + 1.0 - 3.0 > $width){
				$motion = $entity->getMotion();
				$motion->y -= 0.05;
				if($motion->y < -0.13){
					$mount = -0.05 / $motion->y;
					$motion->x *= $mount;
					$motion->z *= $mount;
				}

				if(!$entity->getMotion()->equals($motion)){
					$entity->setMotion($motion);
				}

				$entity->resetFallDistance();

				if($random->nextInt() === 0){
					//TODO.. send sound
				}
			}
		}

		return true;
	}
}