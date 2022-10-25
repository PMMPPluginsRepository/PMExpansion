<?php

declare(strict_types=1);

namespace skh6075\pmexpansion;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\AsyncTask;
use skh6075\pmexpansion\expansion\BlockExpansion;

final class Loader extends PluginBase{
	protected function onEnable() : void{
		$this->synchronizeExpansions();
	}

	private function synchronizeExpansions(): void{
		BlockExpansion::synchronize();

		$this->getServer()->getAsyncPool()->addWorkerStartHook(function(int $worker): void{
			$this->getServer()->getAsyncPool()->submitTaskToWorker(new class extends AsyncTask{
				public function onRun() : void{
					BlockExpansion::synchronize();
				}
			}, $worker);
		});
	}
}