<?php

class page_timer {
	private $start = 0;
	private $finish = 0;
	
	private function getTime() {
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		return $time;
	}
	
	public function start() {
		$this->start = $this->getTime();
	}
	
	public function stop() {
		$this->finish = $this->getTime();
	}
	
	public function elapsed($precision=4) {
		return round(($this->finish - $this->start), $precision);
	}
	
}

?>