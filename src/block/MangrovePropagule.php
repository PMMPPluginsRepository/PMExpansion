<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Block;
use pocketmine\block\Flowable;
use pocketmine\block\VanillaBlocks;
use pocketmine\data\bedrock\block\BlockStateNames;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\data\runtime\RuntimeDataReader;
use pocketmine\data\runtime\RuntimeDataWriter;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\block\utils\IBlockState;

class MangrovePropagule extends Flowable implements IBlockState{
	use BlockTypeIdTrait;

	private bool $hanging = false;
	private int $propagule_stage = 0;

	public function getStateSerialize() : ?Closure{
		return static fn(MangrovePropagule $block): BlockStateWriter => BlockStateWriter::create(BlockTypeNames::MANGROVE_PROPAGULE)
			->writeBool(BlockStateNames::HANGING, $block->isHanging())
			->writeInt(BlockStateNames::PROPAGULE_STAGE, $block->getPropaguleStage());
	}

	public function getStateDeserialize() : ?Closure{
		return static fn(BlockStateReader $in): MangrovePropagule|Block => ExtraVanillaBlocks::MANGROVE_PROPAGULE()
			->setHanging($in->readBool(BlockStateNames::HANGING))
			->setPropaguleStage($in->readInt(BlockStateNames::PROPAGULE_STAGE));
	}

	public function getRequiredStateDataBits() : int{ return 3; }

	protected function describeState(RuntimeDataWriter|RuntimeDataReader $w) : void{
		$w->bool($this->hanging);
		$w->boundedInt(2, 0, 4, $this->propagule_stage);
	}

	public function isHanging(): bool{ return $this->hanging; }

	public function setHanging(bool $state): self{
		$this->hanging = $state;
		return $this;
	}

	public function getPropaguleStage(): int{ return $this->propagule_stage; }

	public function setPropaguleStage(int $stage): self{
		if($stage < 0 || $stage > 4){
			throw new \InvalidArgumentException("PropaguleStage must be in range 0 ... 4");
		}
		$this->propagule_stage = $stage;
		return $this;
	}

	public function onNearbyBlockChange() : void{
		if(!$this->hanging){
			$down = $this->getSide(Facing::DOWN);
			if($down->isSameType(VanillaBlocks::AIR())){
				$this->position->getWorld()->useBreakOn($this->position);
			}
		}else{
			$up = $this->getSide(Facing::UP);
			if(!$up->isSolid()){
				$this->position->getWorld()->useBreakOn($this->position);
			}
		}
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if(Facing::opposite($face) > 1){
			return false;
		}

		$this->hanging = Facing::opposite($face) === 1;

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}
}