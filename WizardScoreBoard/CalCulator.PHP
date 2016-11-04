<?php
class Calculator{
	private static $correction = 10;
	private static $bonus = 20;
	/**
	 * This function will do the calculation
	 * @param int $requested
	 * @param int $received
	 * @return int
	 */
	public function calculate($requested, $received){
		$calc = 0;
		if ($requested == $received){
			$calc = self::bonus + ($received * self::correction);
		}else{
			if ($received > $requested){
				$calc = -(($received-$requested) * self::correction);
			}else{
				$calc = ($received-$requested) * self::correction;
			}
		}
		return $calc;
	}
}