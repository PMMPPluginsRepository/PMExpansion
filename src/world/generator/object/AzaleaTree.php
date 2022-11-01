<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\world\generator\object;

use pocketmine\block\Block;
use pocketmine\block\Dirt;
use pocketmine\block\utils\DirtType;
use pocketmine\block\VanillaBlocks;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use skh6075\pmexpansion\block\ExtraVanillaBlocks;

final class AzaleaTree{
	private function generateRootedDirt(ChunkManager $world, Vector3 $pos): void{
		/** @var Block|Dirt $block */
		$block = $world->getBlockAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
		if(!$block->isSameType(VanillaBlocks::DIRT()) || !$block->getDirtType()->equals(DirtType::ROOTED())){
			$world->setBlockAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), VanillaBlocks::DIRT()->setDirtType(DirtType::ROOTED()));
		}
	}

	private function generateLog(ChunkManager $world, Vector3 $pos): void{
		$block = $world->getBlockAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
		if(
			$block->isSameType(VanillaBlocks::AIR()) ||
			$block->isSameType(ExtraVanillaBlocks::AZALEA()) ||
			$block->isSameType(ExtraVanillaBlocks::AZALEA_LEAVES()) ||
			$block->isSameType(ExtraVanillaBlocks::AZALEA_LEAVES_FLOWERED())
		){
			$world->setBlockAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), VanillaBlocks::OAK_LOG());
		}
	}

	private function generateLeave(ChunkManager $world, Vector3 $pos, Random $random): void{
		$block = $world->getBlockAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());
		if($block->isSameType(VanillaBlocks::AIR())){
			if($random->nextBoundedInt(3) === 1){
				$world->setBlockAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), ExtraVanillaBlocks::AZALEA_LEAVES_FLOWERED());
			}else{
				$world->setBlockAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ(), ExtraVanillaBlocks::AZALEA_LEAVES());
			}
		}
	}

	public function generate(ChunkManager $world, Random $random, Vector3 $pos): void{
		$i = $random->nextBoundedInt(2) + 2;
		$j = $pos->getFloorX();
		$k = $pos->getFloorY();
		$l = $pos->getFloorZ();

		$i2 = $k + $i;

		if($k >= -63 && $k + $i + 2 < 320){
			$down = $pos->down();
			for($il = 0; $il < $i + 1; $il ++){
				$this->generateLog($world, new Vector3($j, $il + $k, $l));
			}
			$this->generateRootedDirt($world, $down);

			for($i3 = -2; $i3 <= 1; ++ $i3){
				for($l3 = -2; $l3 <= 1; ++ $l3){
					$k4 = 1;
					$offsetX = $random->nextRange(0, 1);
					$offsetY = $random->nextRange(0, 1);
					$offsetZ = $random->nextRange(0, 1);
					$this->generateLeave($world, new Vector3($j + $i3 + $offsetX, $i2 + $k4 + $offsetY, $l + $l3 + $offsetZ), $random);
					$this->generateLeave($world, new Vector3($j - $i3 + $offsetX, $i2 + $k4 + $offsetY, $l + $l3 + $offsetZ), $random);
					$this->generateLeave($world, new Vector3($j + $i3 + $offsetX, $i2 + $k4 + $offsetY, $l - $l3 + $offsetZ), $random);
					$this->generateLeave($world, new Vector3($j - $i3 + $offsetX, $i2 + $k4 + $offsetY, $l - $l3 + $offsetZ), $random);

					for($d = 0; $d < 2; $d ++){
						$this->generateLeave($world, new Vector3($j + $i3, $i2 + $d, $l + $l3), $random);
						$this->generateLeave($world, new Vector3($j - $i3, $i2 + $d, $l + $l3), $random);
						$this->generateLeave($world, new Vector3($j + $i3, $i2 + $d, $l - $l3), $random);
						$this->generateLeave($world, new Vector3($j - $i3, $i2 + $d, $l - $l3), $random);
					}

					$k4 = 2;
					$offsetX = $random->nextRange(-1, 0);
					$offsetY = $random->nextRange(-1, 0);
					$offsetZ = $random->nextRange(-1, 0);

					$this->generateLeave($world, new Vector3($j + $i3 + $offsetX, $i2 + $k4 + $offsetY, $l + $l3 + $offsetZ), $random);
					$this->generateLeave($world, new Vector3($j - $i3 + $offsetX, $i2 + $k4 + $offsetY, $l + $l3 + $offsetZ), $random);
					$this->generateLeave($world, new Vector3($j + $i3 + $offsetX, $i2 + $k4 + $offsetY, $l - $l3 + $offsetZ), $random);
					$this->generateLeave($world, new Vector3($j - $i3 + $offsetX, $i2 + $k4 + $offsetY, $l - $l3 + $offsetZ), $random);
				}
			}
		}

		$this->generateLog($world, $pos);
	}
}