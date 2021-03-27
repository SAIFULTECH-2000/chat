<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Auth extends CI_Controller
    {
        public function index()
        {
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');
            if ($this->form_validation->run() == false) {
                $data['title'] = "login";
                //gmail
                require_once 'vendor/autoload.php';
                // init configuration
                $clientID = '<YOUR_CLIENT_ID>';
                $clientSecret = '<YOUR_CLIENT_SECRET>';
                $redirectUri = 'http://localhost/message/';
                //https://console.cloud.google.com/apis/dashboard
                //get your client_id and your client secret at console.cloud.google
                // create Client Request to access Google API
                $client = new Google_Client();
                $client->setClientId($clientID);
                $client->setClientSecret($clientSecret);
                $client->setRedirectUri($redirectUri);
                $client->addScope("email");
                $client->addScope("profile");
                // authenticate code from Google OAuth Flow
                if (isset($_GET['code'])) {
                    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                    $client->setAccessToken($token['access_token']);
                    // get profile info
                    $google_oauth = new Google_Service_Oauth2($client);
                    $google_account_info = $google_oauth->userinfo->get();
                    $email =  $google_account_info->email;
                    $name =  $google_account_info->name;
                    // getting profile information
                    $google_oauth = new Google_Service_Oauth2($client);
                    $google_account_info = $google_oauth->userinfo->get();
                    if (!empty($email)) {
                        //login berjaya
                        $this->_google($email,$name);
                    }

                    // now you can use this profile info to create account in your website and make user logged in.
                } else {
                    //<a href="index.html" class="btn btn-google btn-user btn-block">
                    //<i class="fab fa-google fa-fw"></i> Login with Google
                    //</a>
                    $login = "<a href='" . $client->createAuthUrl() . "'class='btn btn-google btn-user btn-block'><i class='fab fa-google fa-fw'></i> Login with Google</a>'";
                    $this->session->set_flashdata('login', $login);
                }
                $this->load->view('templatelogin/header.php', $data);
                $this->load->view('auth/login');
                $this->load->view('templatelogin/footer');
            }
        }
        private function _google($email,$name)
        {
            $user = $this->db->get_where('users', ['email' => $email])->row_array();
            if ($user) {
                //user ada
                if ($user['is_active'] == 1) {
                    $data = [
                        'email' => $user['email'],
                    ];
                    $this->session->set_userdata($data);
                    redirect('message');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">This email has not been activated!</div>');
                    redirect('auth');
                }
            } else {
                $data = [
                    'username' => $name,
                    'email' => $email,
                    'image' => 'default.jpg',
                    'is_active' => 1, //nanti tukar ini jadi 0
                ];
                $this->db->insert('users', $data);
                $this->session->set_userdata($data['email']);
                redirect('message');
            }
        }
        public function logout()
        {
            $this->session->unset_userdata('email');
            $this->session->unset_userdata('role_id');
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">You have been logged out!</div>');
            redirect('auth');
        }
    }
