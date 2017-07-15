<?php
Namespace Project\Filter;

class PlayBookFilter {

	public function __invoke($app) {
		if (($_SERVER["REDIRECT_URL"]) != '/PlayBook') {
			return true;
		} else {
			return false;
		}
	}

}
