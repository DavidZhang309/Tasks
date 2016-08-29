<?php
class AuthDB extends mysqli
{
	public function __construct()
	{
		parent::__construct("127.0.0.1", "<user>", "<pass>", "<schema name>");
		$this->set_charset("utf8");
	}
}

?>