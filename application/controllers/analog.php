<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once(APPPATH.'controllers/home.php'); 

class Analog extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('common_functions');
        if (!$this->session->userdata('is_admin_login')) {
            redirect('dashboard/login/');
        }
    }
    
    public function index()
    {
        $aViewParameter =   array();
        
        $aViewParameter['page'] ='home';
        $this->load->model('analog_model');
        $aViewParameter['sucess'] =   '0';

        
        $aObjHome = new Home();   
        $aObjHome->checkSettingsSaved(); 
        
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

    public function checkAnalogResponse()
    {
        $seconds = 1.5;
        $micro = $seconds * 1000000;
        $this->load->model('analog_model');

        $aObjHome = new Home();   
        $aObjHome->checkSettingsSaved(); 
        $sResponse =   get_rlb_status();
        $aAP       =   array($sResponse['AP0'],$sResponse['AP1'],$sResponse['AP2'],$sResponse['AP3']);
        //$aAP         =   array(0,1,0,1);

        $sValves        =   $sResponse['valves'];
        $sRelays        =   $sResponse['relay'];
        $sPowercenter   =   $sResponse['powercenter'];

        $aResult    =   $this->analog_model->getAllAnalogDevice();
        $iResultCnt =   count($aResult);
        for($i=0; $i<$iResultCnt; $i++)
        {
            if($aResult[$i] != '')
            {
                $aDevice = explode('_',$aResult[$i]);
                if($aDevice[1] != '')
                {
                    if($aDevice[1] == 'R')
                    {
                        $sNewResp = replace_return($sRelays, $aAP[$i], $aDevice[0] );
                        onoff_rlb_relay($sNewResp);
                    }
                    if($aDevice[1] == 'P')
                    {
                        $sNewResp = replace_return($sPowercenter, $aAP[$i], $aDevice[0] );
                        onoff_rlb_powercenter($sNewResp);
                    }
                    if($aDevice[1] == 'V')
                    {
                        $sNewResp = replace_return($sValves, $aAP[$i], $aDevice[0] );
                        onoff_rlb_valve($sNewResp);
                    }
                }
            }
        }

        usleep($micro);
    }
}

?>
