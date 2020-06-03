<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Request;
use CodeIgniter\Validation\Validation as ValidationValidation;
use Config\Validation;
use App\Models\UserModel;
use phpDocumentor\Reflection\Types\String_;
use phpDocumentor\Reflection\Types\This;

class User extends BaseController
{

	public function __construct()
	{
		helper(['form', 'url', 'text']);
	
	}
	public function index()
	{

		$data = ['title' => "User Login", "action" => site_url() . "user", "registerlink" => site_url() . "user/register", "forgetpwdlink" => site_url() . "user/forgetPassword"];

		if ($this->request->getMethod() == 'post') {
			$rule = [
				'email' => [
					'label' => 'Email',
					'rules' => 'required|valid_email'
				],
				'password' => [
					'label' => 'Password',
					'rules' => 'required|min_length[6]',
				]

			];

			$error = [
				'password' => ['min_length' => 'Minimum 7 character need for Full Name'],
				'email' => ['min_length' => 'Minimum 5 character need for Full Name']
			];

			if (!$this->validate($rule, $error)) {

				$data['validation1'] = $this->validator->getErrors();
			} else {
				$User = new UserModel();
				$userdata = $User->where('email', $this->request->getPost('email'))->first();
					if($this->request->getPost('remember')){
					$token = random_string('md5', 32);
					set_cookie('remembermetoken', $token,'3600'); // with cookies working code 
				
					 $User->set('remember_token',$token);
					 $User->where('email',$this->request->getPost('email'));
					 $User->update();
					// the above three line of code produce 
					//UPDATE `user` SET = '' WHERE `email` = 'pal@gmail.com'
					// why it is not setting remember_token
					//contrary to that if i use 
					// $User->query("UPDATE `user` SET `remember_token`='".$token."' WHERE `email`= '".$this->request->getPost('email')."'");
					// it will produce correct query ?	 
					//UPDATE `user` SET `remember_token`='d0dc17846d297bb28ac37e16e280060d' WHERE `email`= 'pal@gmail.com'

					echo $User->getLastQuery(); 	
					//echo (string)$User->affectedRows();
					//print_r($User->error());
					die;
	
				}

				if (!empty($userdata)) {
					if (password_verify($this->request->getPost('password'), $userdata['password'])) {
						$userlogin = ['id' => $userdata['id'], 'email' => $userdata['email'], 'username' => $userdata['username']];
						session()->set('userlogin', $userlogin);
						return view('user/userDashboard');
					} else {
						session()->setFlashdata('msg', 'Password Not Correct');
					}
				} else {
					session()->setFlashdata('msg', 'user not availabel by this Email id');
				}
			}
		}
		return view('user/login', $data);
	}

	public function userlogout()
	{
		session()->remove('userlogin');
		return redirect()->to(site_url());
	}

	public function userdashboard()
	{
		if (session()->has('userlogin')) {
			return view('user/userDashboard');
		} else {

			return redirect()->to(site_url());
		}
	}

	public function register()
	{

		$data = ['title' => "Register User", "action" => site_url() . "user/register", "loginlink" => site_url() . "user"];

		if ($this->request->getMethod() == 'post') {
			$rule = [
				'fullname' => [
					'label' => 'Full Name',
					'rules' => 'required|min_length[5]'
				],
				'email' => [
					'label' => 'Email',
					'rules' => 'required|valid_email|is_unique[user.email]'
				],
				'password' => [
					'label' => 'Password',
					'rules' => 'required|min_length[6]',
				],
				'password_again' => [
					'label' => 'Confirm Password',
					'rules' => 'matches[password]'
				],
				'terms' => [
					'label' => "Term And Condition",
					'rules' => 'required'
				]

			];

			$error = [
				'password' => ['min_length' => 'Minimum 7 character need for Full Name'],
				'password_again' => ['matches' => 'Confirm Password Must match password'],
				'email' => ['min_length' => 'Minimum 5 character need for Full Name'],
				'fullname' => ['min_length' => "Minimum 5 character need for Full Name"],
				'terms' => ['required' => 'Term and Condition must be Accepted']
			];

			if (!$this->validate($rule, $error)) {

				$data['validation1'] = $this->validator->getErrors();
			} else {
				$User = new UserModel();
				$newdata = [
					'username' => $this->request->getPost('fullname'),
					'email' => $this->request->getPost('email'),
					'password' => $this->request->getPost('password')
				];

				if ($User->insert($newdata)) {

					$email = \Config\Services::email();

					$email->setFrom('stackpalk@gmail.com', 'stackpalk');
					$email->setTo($newdata['email']);

					$email->setSubject('Welcome Registration Mail with attachment');
					$email->setMessage('<table><tr>
					<td>this is data part of 1 row</td>
					<td>this is data part of 1 row</td>
					<td>this is data part of 1 row</td>
				</tr>
				<tr>
					<td>this is data part of the 2 row</td>
					<td>this is data part of the 2 row</td>
					<td>this is data part of the 2 row</td>
				</tr>
				<tr>
					<td>this is data part of the 3 row</td>
					<td>this is data part of the 3 row</td>
					<td>this is data part of the 3 row</td>
				</tr></table>
			');

					$email->send();
					$session = session();
					$session->setFlashdata('successmsg', 'form submitted successfully');
				} else {
					$session = session();
					$session->setFlashdata('failmsg', 'form Submission Fail Server side error ');
				}
			}
		}
		return view('user/register', $data);
	}

	public function forgetPassword()
	{

		$data = ['title' => "Forget Password", "action" => site_url() . "user/forgetPassword", "registerlink" => site_url() . "user/register", "loginlink" => site_url() . "user"];
		if ($this->request->getMethod() === 'post') {
		}
		$rule = [
			'email' => [
				'label' => 'Email',
				'rules' => 'required|valid_email'
			]
		];

		if (!$this->validate($rule)) {
			$data['validation'] = $this->validator->getErrors();
		} else {

			$User = new UserModel();
			$userdata = $User->where('email', $this->request->getPost('email'))->first();

			if (!empty($userdata)) {
				$token = random_string('md5', 32);
				$db = \Config\Database::connect();
				$dtim = date('Y-m-d H:m:i', time());
				$db->query("INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES ('" . $userdata['email'] . "','" . $token . "','" . $dtim . "')");
				$query = $db->getLastQuery();
				if ($db->affectedRows()) {

					$email = \Config\Services::email();

					$email->setFrom('stackpalk@gmail.com', 'stackpalk');
					$email->setTo($userdata['email']);
					$email->setSubject('Reset Passwrod Mail');
					$email->setMessage('<h12>Please Click the Following link to Reset Password It is valid only For 1 hour</h2>
				<a targer="_blank" href="' . site_url() . 'user/recoverpassword?token=' . $token . '">Directory access is forbidden.</p>');

					if ($email->send()) {
						helper('cookie');
						$this->response->setCookie('forgetpwd', $token, (time() + 3600), "/", site_url());
					} else {
						echo 'some problem accured here in sendign mail';
						die();
					}
				}
			} else {

				return redirect()->back()->with('msg', 'Email Not Availabel With us');
			}
		}
		return view('user/forgetPassword', $data);
	}

	public function recoverpassword()
	{
		$data = ['title' => "Forget Password", "action" => site_url() . "user/recoverpassword", "registerlink" => site_url() . "user/register", "loginlink" => site_url() . "user"];
		$errmsg = '';

		if ($this->request->getMethod() == 'post') {
			$rule = [
				'password' => [
					'label' => 'Password',
					'rules' => 'required|min_length[6]',
				],
				'password_again' => [
					'label' => 'Confirm Password',
					'rules' => 'matches[password]'
				]
			];
			$error = [
				'password' => ['min_length' => 'Minimum 7 character need for Full Name'],
				'password_again' => ['matches' => 'Confirm Password Must match password']
			];

			if (!$this->validate($rule, $error)) {
				$data['verror'] = $this->validator->getErrors();
				return view('/user/recoverPassword', $data);
			} else {
				$db = \Config\Database::connect();
				$resobj = $db->query("SELECT * FROM `password_resets` WHERE token = '" . $this->request->getPost('_token') . "'")->getRow();
				if ($resobj) {
					if (strtotime($resobj->created_at) >= (time() - 3600)) {
						$User = new UserModel();
						$newdata = [
							'password' => $this->request->getPost('password')
						];
						$userdata = $User->where('email', $resobj->email)->first();
						$User->update($userdata['id'], $newdata);
						if ($db->affectedRows()) {
							$db->query("DELETE FROM `password_resets` WHERE `email`='" . $resobj->email . "'");
							session()->setFlashdata('smsg', 'Password Updated Successfully');
							return redirect()->to(site_url() . 'user');
						} else {
							throw new \CodeIgniter\Database\Exceptions\DatabaseException();
						}
					}
				} else {
				}
			}
		}
		$token = $this->request->getGet('token');
		if ($token == $this->request->getCookie('forgetpwd')) {
			$resobj = $db->query("SELECT * FROM `password_resets` WHERE token = '" . $token . "'")->getRow();
			if ($resobj) {
				if (strtotime($resobj->created_at) >= (time() - 3600)) {
					$data['token'] = $token;
					return view('/user/recoverPassword', $data);
				} else {
					$errmsg = "Your Token is Expired Regenerate Password Reset Mail to Get new Token";
					echo $errmsg;
				}
			} else {
				$errmsg = 'Your Token is not valid';
				echo $errmsg;
				die();
			}
		}
	}
}
