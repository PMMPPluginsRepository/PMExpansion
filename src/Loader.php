<?php

declare(strict_types=1);

namespace skh6075\pmexpansion;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\AsyncTask;
use skh6075\pmexpansion\expansion\BlockExpansion;
use skh6075\pmexpansion\expansion\EntityExpansion;
use skh6075\pmexpansion\expansion\ItemExpansion;

final class Loader extends PluginBase{
	protected function onEnable() : void{
		$this->synchronizeExpansions();
	}

	private function synchronizeExpansions(): void{
		EntityExpansion::synchronize();
		BlockExpansion::synchronize();
		ItemExpansion::synchronize();

		$this->getServer()->getAsyncPool()->addWorkerStartHook(function(int $worker): void{
			$this->getServer()->getAsyncPool()->submitTaskToWorker(new class extends AsyncTask{
				public function onRun() : void{
					BlockExpansion::synchronize();
					ItemExpansion::synchronize();
				}
			}, $worker);
		});
	}
}