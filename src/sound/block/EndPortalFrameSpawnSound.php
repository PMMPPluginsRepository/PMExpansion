<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\sound\block;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\world\sound\Sound;

final class EndPortalFrameSpawnSound implements Sound{
	public function encode(Vector3 $pos) : array{
		return [LevelSoundEventPacket::nonActorSound(LevelSoundEvent::BLOCK_END_PORTAL_SPAWN, $pos, false)];
	}
}