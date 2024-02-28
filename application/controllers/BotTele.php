<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BotTele extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->load->view('welcome_message');
    }

    public function insertDataWfh147() {
        date_default_timezone_set("Asia/Bangkok");
        $data147 = $this->getDataWfh147();
        for ($i=0; $i < count($data147); $i++) {     
            $data = array(
                'update_id'=>$data147[$i]['update_id'],
                'message_id'=>$data147[$i]['message_id'],
                'user_id'=>$data147[$i]['id_user'],
                'first_name'=>$data147[$i]['first_name'],
                'username'=>$data147[$i]['username'],
                'group_id'=>$data147[$i]['chat_id'],
                'file_id'=>$data147[$i]['file_id'],
                'tanggal'=>date("Y-m-d H:i:s",$data147[$i]['date']),
                'kendala'=>$data147[$i]['kendala']
            );
            $dataIdUpdate = $this->db->get_Where('message_147', array('update_id'=> $data147[$i]['update_id']))->row_array();
            if(!(isset($dataIdUpdate['update_id']))){

                $this->db->insert('message_147',$data);
            }
        }
    }

    public function getDataWfh147()
    {
        $token = "1770769528:AAFsK0u6CHy-CVNm95KimZt7gYQEgEqE0tk";
        $link = "https://api.telegram.org/bot" . $token . "/getUpdates";
        // $curl = $this->getCurl($link);
        $curl147 = $this->getCurl147($link);
        $totalData = COUNT($curl147['result']);
        $dataArray147 = array();
        for ($i=0; $i < $totalData; $i++) { 
            $dataArray147[$i]['update_id'] = $curl147['result'][$i]['update_id'];
            $dataArray147[$i]['message_id'] = $curl147['result'][$i]['message']['message_id'];
            $dataArray147[$i]['id_user'] = $curl147['result'][$i]['message']['from']['id'];
            $dataArray147[$i]['first_name'] = $curl147['result'][$i]['message']['from']['first_name'];

            if(isset($curl147['result'][$i]['message']['from']['username'])) {
                $dataArray147[$i]['username'] = $curl147['result'][$i]['message']['from']['username'];
            }
            else{
                $dataArray147[$i]['username'] = '';
            }
            
            $dataArray147[$i]['chat_id'] = $curl147['result'][$i]['message']['chat']['id'];
            $dataArray147[$i]['date'] = $curl147['result'][$i]['message']['date'];

            if(isset($curl147['result'][$i]['message']['text'])) {
                $dataArray147[$i]['kendala'] = $curl147['result'][$i]['message']['text'];
            }
            else {
                $dataArray147[$i]['kendala'] = '';
                if(isset($curl147['result'][$i]['message']['caption'])) {
                    $dataArray147[$i]['kendala'] = $curl147['result'][$i]['message']['caption'];
                }
                else {
                    $dataArray147[$i]['kendala'] = '';
                }
            }

            if(isset($curl147['result'][$i]['message']['photo'])) {
                $dataArray147[$i]['file_id'] = $curl147['result'][$i]['message']['photo'][3]['file_id'];
            }
            else{
                $dataArray147[$i]['file_id'] = '';
            }

            
            
        }
        // print_r($curl147['result']);
        // print_r($curl147['result'][0]['message']['from']['username']);
        return $dataArray147;
    }

    public function getCurl147($link)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        if ($e = curl_error($ch)) {
            echo $e;
        } else {
            $data = json_decode($output, TRUE);
        }
        curl_close($ch);
        return $data;
    }

    public function cek(){
        phpinfo();
    }
}
