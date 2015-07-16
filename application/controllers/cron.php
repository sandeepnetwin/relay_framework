<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cron extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function index()
    {
        $seconds = 2;
        $micro = $seconds * 1000000;
        $this->load->model('analog_model');
        $myFile = "/var/www/relay_framework/daemontest1.txt";
         $fh = fopen($myFile, 'a') or die("Can't open file");
         $stringData = "File updated at: " . $sResponse. "\n";
         fwrite($fh, $stringData);
         fclose($fh);
        $this->load->model('home_model');
        list($sIpAddress, $sPortNo) = $this->home_model->getSettings();
        
        if($sIpAddress == '')
        {
            if(IP_ADDRESS){
                $sIpAddress = IP_ADDRESS;
            }
        }
        
        //Check for Port Number constant
        if($sPortNo == '')
        {   
            if(PORT_NO){
                $sPortNo = PORT_NO;
            }
        }

        if($sIpAddress == '' || $sPortNo == '')
        {

        }   
        else
        { 
            $sResponse =   get_rlb_status();
            $aAP       =   array($sResponse['AP0'],$sResponse['AP1'],$sResponse['AP2'],$sResponse['AP3']);
            $aAP         =   array(0,1,0,1);

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
}

?>
