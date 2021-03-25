<?php

class Home extends Controller
{
	public function index($page = 'index'){
		if($page == 'index'){
			$config = $this->model('Config');
			$config->set('title', 'This is Index!');

			$this->view('index', $config->load());
		}
		else {
			echo "<h3>" . $page . "</h3>";
		}
	}
}
