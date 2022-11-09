<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block\utils;

trait BlockAgeTrait{
	protected int $age = 0;

	abstract protected function getMaxAge(): int;

	public function getAge(): int{ return $this->age; }

	public function setAge(int $age): self{
		if($age < 0 || $age > $this->getMaxAge()){
			throw new \InvalidArgumentException("Age must be in range 0 ... " . $this->getMaxAge());
		}
		$this->age = $age;
		return $this;
	}
}