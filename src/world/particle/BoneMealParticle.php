<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\world\particle;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelEvent;
use pocketmine\world\particle\Particle;

final class BoneMealParticle implements Particle{
	public function encode(Vector3 $pos) : array{
		return [LevelEventPacket::create(LevelEvent::BONE_MEAL_USE, 0, $pos)];
	}
}