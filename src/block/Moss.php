<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Block;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\Opaque;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Fertilizer;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\SpawnParticleEffectPacket;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\player\Player;
use pocketmine\utils\Random;
use pocketmine\world\Position;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\block\utils\IBlockState;

class Moss extends Opaque implements IBlockState{
	use BlockTypeIdTrait;

	public function getStateSerialize() : ?Closure{ return null; }

	public function getStateDeserialize() : ?Closure{ return null; }

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($item instanceof Fertilizer){
			$this->generateMossBlock();
			$this->populatePlantsRegion();
			$this->position->world->broadcastPacketToViewers($this->position, SpawnParticleEffectPacket::create(
				dimensionId: DimensionIds::OVERWORLD,
				actorUniqueId: -1,
				position: $this->position->add(0.5, 0.5, 0.5),
				particleName: "minecraft:crop_growth_emitter",
				molangVariablesJson: ""
			));
			$item->pop();

			return true;
		}

		return false;
	}

	private function canGenerateMoss(Block $block): bool{
		static $blocks = [
			BlockTypeIds::GRASS => true,
			BlockTypeIds::DIRT => true,
			BlockTypeIds::STONE => true,
			BlockTypeIds::MYCELIUM => true,
			BlockTypeIds::DEEPSLATE => true,
			BlockTypeIds::TUFF => true
		];
		return isset($blocks[$block->getTypeId()]);
	}

	private function canGrowPlant(Position $pos): bool{
		return $this->canGenerateMoss($this->position->getWorld()->getBlock($pos->down()));
	}

	private function canBePopulated(Position $pos): bool{
		return ($down = $this->position->getWorld()->getBlock($pos->down()))->isSolid()
			&& $down->getTypeId() !== MossCarpet::getFixedTypeId()
			&& $this->position->getWorld()->getBlock($pos)->getTypeId() === BlockTypeIds::AIR;
	}

	private function canBePopulatedToBlockAir(Position $pos): bool{
		return $this->canBePopulated($pos) && $this->position->getWorld()->getBlock($pos->up())->getTypeId() === BlockTypeIds::AIR;
	}

	private function generateMossBlock(): void{
		$random = new Random();
		$center = $this->position;
		$world = $center->world;
		for($x = $center->getFloorX() - 3; $x <= $center->getFloorX() + 3; $x ++){
			for($z = $center->getFloorZ() - 3; $z <= $center->getFloorZ() + 3; $z ++){
				for($y = $center->getFloorY() + 5; $y >= $center->getFloorY() - 5; $y --){
					$block = $world->getBlockAt($x, $y, $z);
					if($this->canGenerateMoss($block) && ($random->nextFloat() < 0.6 || abs($x - $center->getFloorX()) < 3 && abs($z - $center->getFloorZ()) < 3)){
						$world->setBlock($block->getPosition(), ExtraVanillaBlocks::MOSS());
						break;
					}
				}
			}
		}
	}

	private function populatePlantsRegion(): void{
		$random = new Random();
		$center = $this->position;
		$world = $center->world;
		for($x = $center->getFloorX() - 3; $x <= $center->getFloorX() + 3; $x ++){
			for($z = $center->getFloorZ() - 3; $z <= $center->getFloorZ() + 3; $z ++){
				for($y = $center->getFloorY() + 5; $y >= $center->getFloorY() - 5; $y--){
					$block = $world->getBlockAt($x, $y, $z);
					$position = $block->getPosition();
					if($this->canBePopulated($position)){
						if(!$this->canGrowPlant($position)){
							break;
						}
						$randomDouble = $random->nextFloat();
						if($randomDouble >= 0 && $randomDouble < 0.3125){
							$world->setBlock($position, VanillaBlocks::TALL_GRASS());
						}
						if($randomDouble >= 0.3125 && $randomDouble < 0.46875){
							$world->setBlock($position, ExtraVanillaBlocks::MOSS_CARPET());
						}
						if($randomDouble >= 0.46875 && $randomDouble < 0.53125){
							if($this->canBePopulatedToBlockAir($position)){
								$world->setBlock($position, VanillaBlocks::DOUBLE_TALLGRASS()->setTop(false));
								$world->setBlock($position->up(), VanillaBlocks::DOUBLE_TALLGRASS()->setTop(true));
							}else{
								$world->setBlock($position, VanillaBlocks::TALL_GRASS());
							}
						}
						if($randomDouble >= 0.53125 && $randomDouble < 0.575){
							$world->setBlock($position, ExtraVanillaBlocks::AZALEA());
						}
						if($randomDouble >= 0.575 && $randomDouble < 0.6){
							$world->setBlock($position, ExtraVanillaBlocks::FLOWERING_AZALEA());
						}
						if($randomDouble >= 0.6 && $randomDouble < 1){
							$world->setBlock($position, VanillaBlocks::AIR());
						}
						break;
					}
				}
			}
		}
	}
}