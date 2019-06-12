<?php
use \Model\Demo;

class Controller_Home extends Controller_Template{
	
	public function action_welcome(){
		$data = array();
		$this->template->title = 'Report: Homepage';
		$this->template->content = View::forge('home/welcome',$data);
	}
	
	public function action_about(){
		$data = array();
		$this->template->title = 'Report: About Us';
		$this->template->content = View::forge('home/about',$data);
	}
	
	public function action_whatif(){
		#$data = array()
		$data['decode'] = Demo::getOriginal();
		$this->template->title = 'Report: What If';
		$this->template->content = View::forge('home/whatif',$data);
	}
	
	public function action_request(){
		$data = array();
		$this->template->title = 'Report: Request Demo';
		$this->template->content = View::forge('home/request',$data);
	}
	

/* ----------- Sending POST requests to Model ------------*/
	
	public function post_submit() {
		$index = $_POST['myDropDown'];
		$data['decode'] = Demo::getData($index);
		$this->template->title = 'Report: What If';
		$this->template->content = View::forge('home/whatif',$data);
	}
	
	public function post_whatif() {
		$changes = [];
		foreach($_POST as $key => $val){
			$changes[$key] = $val;
		}
		$data['decode'] = Demo::getOriginal();
		$inputs['decode'] = Demo::changeData($data, $changes);
		$this->template->title = 'Report: What If';
		$this->template->content = View::forge('home/whatif',$inputs);
	}
	
	public function post_request() {
		
		$first = Input::post('first');
		$last = Input::post('last');
		$email = Input::post('email');
		$request = Input::post('request');
		
		Demo::send_request($first, $last, $email, $request);
		
		$data = array();
		$this->template->title = 'Report: Request Demo';
		$this->template->content = View::forge('home/request',$data);
	}
}
