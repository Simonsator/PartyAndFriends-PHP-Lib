<?php
namespace PHPSTORM_META {
	override(
	// Virtual function to indicate that all SQL
	// injections will have the following replacement rules.
		sql_injection_subst(),
		map([
			'{$this->getTablePrefix()}' => 'fr_',
		]));
}