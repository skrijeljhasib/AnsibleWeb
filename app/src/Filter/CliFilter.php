<?php
Namespace Project\Filter;

class CliFilter {

	public function __invoke($app) {
		if (php_sapi_name() != 'cli') {
			return true;
		} else {
			return false;
		}
	}

}
