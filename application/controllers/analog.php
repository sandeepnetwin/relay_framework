<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Analog extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('common_functions');
    }
    
    public function index()
    {
        $aViewParameter =   array();
        
        $aViewParameter['page'] ='home';
        $this->load->model('analog_model');
        $aViewParameter['sucess'] =   '0';

        require_once(APPPATH.'controllers/home.php'); 
        $aObj = new Home();   
        $aObj->checkSettingsSaved(); 
        
        $sResponse      =   get_rlb_status();

        $sValves        =   $sResponse['valves'];
        $sRelays        =   $sResponse['relay'];
        $sPowercenter   =   $sResponse['powercenter'];
        
        $aViewParameter['sValves']          =   $sValves;
        $aViewParameter['sRelays']          =   $sRelays;
        $aViewParameter['sPowercenter']     =   $sPowercenter; 

        $aViewParameter['relay_count']      =   strlen($sRelays);
        $aViewParameter['valve_count']      =   strlen($sValves);
        $aViewParameter['power_count']      =   strlen($sPowercenter);

        if($this->input->post('command') == 'Save')
        {
             $sDeviceName = $this->input->post('sDeviceName');
             $this->analog_model->saveAnalogDevice($sDeviceName);
             $aViewParameter['sucess'] =   '1';
        }

        $aAllAnalogDevice   =   $this->analog_model->getAllAnalogDevice();

        
        $aViewParameter['aResponse']    =   array('AP0' => $sResponse['AP0'],
                                                  'AP1' => $sResponse['AP1'],
                                                  'AP2' => $sResponse['AP2'],
                                                  'AP3' => $sResponse['AP3']);

        $aViewParameter['aAllAnalogDevice']  =   $aAllAnalogDevice;  

        $this->load->view('Analog',$aViewParameter);
    }
}

?>
