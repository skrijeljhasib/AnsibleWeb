<?php
Namespace Project\Filter;

class PlayBookFilter {

	public function __invoke($app) {
		if (($_SERVER["REDIRECT_URL"]) != '/PlayBook') {
			return true;
		};
		if (($_SERVER["REDIRECT_URL"]) != '/redeploy') {
			return true;
                };
		return false;
	}

}
