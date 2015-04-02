<?php
/* copyright - Catalin Alin 2015 (catalin@live.jp) */
class router {
	const NOAUTH = 0;
	const NEEDAUTH = 1;
	
	private $routerList = array();
	private $scriptPath = "_System/_Modules/news.php"; //Default
	private $authRequired = self::NOAUTH; //Default
	private $pageTitle = "News"; //Default



	public function __construct()
	{
		//init available routes.
		// [Request Page] [Page Title] [Script Path] [[NOAUTH / NEEDAUTH]]
		$this->addRoute("404", "Page Not Found!", "_System/_Modules/404.php", self::NOAUTH);
		$this->addRoute("guide", "Game Guide", "_System/_Modules/guide.php", self::NOAUTH);
		$this->addRoute("downloads", "Download Game", "_System/_Modules/downloads.php", self::NOAUTH);
		$this->addRoute("login", "Login to your account", "_System/_Modules/login.php", self::NOAUTH);
		
	}

	private function addRoute($match, $pageTitle, $routeScript, $AUTH)
	{
		array_push($this->routerList,  array($match, $pageTitle, $routeScript, $AUTH));
	}
	
	
	public function Init($REQUEST){
		foreach ($this->routerList as &$value) {	
			if(strcmp($REQUEST,$value[0]) == 0)
			{
				$this->pageTitle = $value[1]; //#1
				$this->scriptPath = $value[2]; //#2
				$this->authRequired = $value[3]; //#3
				return;
			}
		}
	}

	/*
	*	Dispatch script
	*/
	public function dispatch()
	{
		if($this->authRequired == self::NEEDAUTH)
		{
			header("Location: /login");
			exit;
		}elseif($this->authRequired == self::NOAUTH)
		{
			if(file_exists($this->scriptPath) == true)
			{
				include($this->scriptPath);
			}else{
				//404 handler.
				header("Location: /404");

			}
		}
	}

	/**
	*	Forces router to load script.
	*/
	public function force($scriptPath)
	{
		if(file_exists($scriptPath) == true)
		{
			include($scriptPath);
		}	
	}
	
	public function getPageTitle()
	{
		return $this->pageTitle;
	}

	private function getRouterList()
	{
		return $this->routerList;
	}

}
?>
