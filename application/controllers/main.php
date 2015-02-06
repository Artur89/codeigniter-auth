<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index(){
		$this->login();
	}

	public function login(){
		$this->load->view('login_view');
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('main/login');
	}

	public function signup(){
		$this->load->view('signup_view');
	}

	public function members(){
		if( $this->session->userdata('is_authenticated') ){
			$this->load->view('member_view');
		}else{
			redirect('main/restricted');
		}
	}

	public function restricted(){
		$this->load->view('restricted_view');
	}

	public function login_validation(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email','Email','required|trim|xss_clean|callback_validate_credentials');
		$this->form_validation->set_rules('password','Password','required|md5|trim|xss_clean');
		$check = $this->form_validation->run();

		if( $check == true ){
			// Set session data
			$data = array(
				'email' => $this->input->post('email'),
				'is_authenticated' => 1
			);
			$this->session->set_userdata($data);

			redirect('main/members');
		}else{
			$this->load->view('login_view');
		}
	}

	public function signup_validation(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[8]');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]');

		$this->form_validation->set_message('is_unique', 'User with this email already exists.');

		if( $this->form_validation->run() ){
			$this->load->library('email', array( 'mailtype' => 'html' ));

			$key = md5(uniqid());

			$this->email->from('no-reply@estmade.ee', 'Estmade');
			$this->email->to( $this->input->post('email') );
			$this->email->subject('Confirm your account.');
			$message = '<p>Thank you for signing up.</p>';
			$message .= "<p><a href='".base_url()."main/register_user/".$key."'>Click here</a> to confirm your account.</p>";
			$this->email->message($message);

			$this->load->model('users_model');

			if( $this->users_model->add_temp_user($key) ){
				if( $this->email->send() ){
					echo 'Email has been sent.';
				}else{
					echo $this->email->print_debugger();
				}
			}else{
				echo 'Problem adding user to database.';
			}
		}else{
			$this->load->view('signup_view');
		}
	}

	public function validate_credentials(){
		$this->load->model('users_model');

		if( $this->users_model->can_log_in() ){
			return true;
		}else{
			$this->form_validation->set_message('validate_credentials', 'Incorrect login or password.');
			return false;
		}
	}

	public function register_user($key){
		$this->load->model('users_model');
		if( $this->users_model->is_key_valid() ){
			if( $email = $this->users_model->add_user($key) ){
				$data = array(
					'email' => $email,
					'is_authenticated' => 1
				);
				$this->session->set_userdata($data);
				redirect('/main/members');
			}else{
				echo 'Error adding user. Please try again later.';
			}
		}else{
			echo 'Invalid key.';
		}
	}

}