<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use pocketmine\block\Opaque;
use pocketmine\block\utils\FacesOppositePlacingPlayerTrait;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\data\runtime\RuntimeDataReader;
use pocketmine\data\runtime\RuntimeDataWriter;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\Shovel;
use pocketmine\item\VanillaItems;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\sound\FizzSound;
use pocketmine\world\sound\FlintSteelSound;
use pocketmine\world\sound\ItemFrameAddItemSound;
use skh6075\pmexpansion\block\tile\BaseCampfireTile as CampfireTile;
use skh6075\pmexpansion\block\utils\IBlockState;

abstract class BaseCampfire extends Opaque implements IBlockState{
	use FacesOppositePlacingPlayerTrait;
	use HorizontalFacingTrait;

	private bool $extinguish = false;

	public function isExtinguished() : bool{ return $this->extinguish; }

	public function setExtinguish(bool $state) : self{
		$this->extinguish = $state;
		return $this;
	}

	protected function describeState(RuntimeDataWriter|RuntimeDataReader $w) : void{
		$w->horizontalFacing($this->facing);
		$w->bool($this->extinguish);
	}

	protected function recalculateCollisionBoxes() : array{
		return [AxisAlignedBB::one()->trim(Facing::UP, 0.5)];
	}

	public function getRequiredStateDataBits() : int{ return 3; }

	public function hasEntityCollision() : bool{
		return true;
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if(!$this->extinguish){
			if($face === Facing::UP && $item instanceof Shovel){
				$block = clone $this;
				$block->extinguish = true;
				$this->position->getWorld()->setBlock($this->position, $block);
				$this->position->getWorld()->addSound($this->position, new FizzSound());

				return true;
			}

			if($player !== null){
				$tile = $this->position->getWorld()->getTile($this->position);
				if($tile instanceof CampfireTile && $tile->addItem($item)){
					$item->pop();
					$this->position->getWorld()->setBlock($this->position, $this);
					$this->position->getWorld()->addSound($this->position, new ItemFrameAddItemSound()); //lol
				}

				return true;
			}
		}elseif($item->getTypeId() === ItemTypeIds::FLINT_AND_STEEL){
			$block = clone $this;
			$block->extinguish = false;
			$this->position->getWorld()->setBlock($this->position, $block);
			$this->position->getWorld()->addSound($this->position, new FlintSteelSound());

			return true;
		}

		return false;
	}

	public function onEntityInside(Entity $entity) : bool{
		if($this->extinguish || ($entity instanceof Player && !$entity->hasFiniteResources()) || $entity->isOnFire()){
			return false;
		}

		$entity->setOnFire(8);
		$entity->attack(new EntityDamageByBlockEvent($this, $entity, EntityDamageEvent::CAUSE_FIRE, 1));

		return true;
	}

	public function onScheduledUpdate() : void{
		if($this->extinguish){
			return;
		}

		$tile = $this->position->getWorld()->getTile($this->position);
		if(!$tile instanceof CampfireTile || $tile->isClosed()){
			return;
		}

		$canChange = false;
		foreach($tile->getContents() as $slot => $item){
			$tile->increaseSlotTime($slot);
			if($tile->getItemTime($slot) < 30){
				continue;
			}

			$tile->setItem(VanillaItems::AIR(), $slot);
			$tile->setSlotTime($slot, 0);

			$this->position->getWorld()->dropItem(
				source: $this->position->add(0, 1, 0),
				item: $tile->getCookResultItem($item)
			);
			$canChange = true;
		}

		if($canChange){
			$this->position->getWorld()->setBlock($this->position, $this);
		}

		$this->position->getWorld()->scheduleDelayedBlockUpdate($this->position, 20);
	}
}