<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Message extends CI_Controller
    {
         public function index(){
            $this->form_validation->set_rules('id_friend', 'id_friend', 'required');
           if( $this->form_validation->run() == FALSE){
              $id=$this->session->userdata('email');
              $data['users'] = $this->db->query("SELECT * FROM users where email='$id'")->row_array();
            $this->load->view('message',$data);
           }else{
              if(!empty($this->input->post('chat'))){
               $id=$this->session->userdata('email');
                 $sent= $this->db->query("SELECT * FROM users where email='$id'")->row_array();
                 $id_sent =$sent['id'];
               $data = array(
                  'id_sent' => $id_sent,
                  'id_received' => $this->input->post('id_friend'),
                  'message' => $this->input->post('chat'),
                  'time' => time()
               );
          $this->db->insert('users_message', $data);
              }
            $id=$this->session->userdata('email');
            $data['id_friend']= $this->input->post('id_friend');
            $data['users'] = $this->db->query("SELECT * FROM users where email='$id'")->row_array();
            $this->load->view('message1',$data);
           }
         }
    }