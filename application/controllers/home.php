<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller 
{

    public function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('common_functions');
    }

    public function index() 
    {
        $aViewParameter['page'] ='home';

        $this->checkSettingsSaved();

        $sResponse      =   get_rlb_status();
        //$sResponse      =   array('valves'=>'0120','powercenter'=>'0000','time'=>'','relay'=>'0000');
        $sValves        =   $sResponse['valves'];
        $sRelays        =   $sResponse['relay'];
        $sPowercenter   =   $sResponse['powercenter'];
        $sTime          =   $sResponse['time'];
          
        $aViewParameter['relay_count']  =   strlen($sRelays);
        $aViewParameter['valve_count']  =   strlen($sValves);
        $aViewParameter['power_count']  =   strlen($sPowercenter);
        $aViewParameter['time']         =   $sTime;

        $this->load->view('Home',$aViewParameter);

    }

    public function setting()
    {
        $aViewParameter['page']         =   'home';
        $aViewParameter['sucess']       =   '0';
        $aViewParameter['err_sucess']   =   '0';
        
        $sPage  =   $this->uri->segment('3'); 

        $this->load->model('home_model');

        $aViewParameter['iActiveMode'] =    $this->home_model->getActiveMode();

        if($sPage == '')
        {
            $aViewParameter['page']         =   'setting';
            
            if($this->input->post('command') == 'Save Setting')
            {
                $iMode  =   $this->input->post('relay_mode');
                $this->load->model('home_model');
                
                $sIP    =   $this->input->post('relay_ip_address');
                $sPort  =   $this->input->post('relay_port_no');

                if($sIP == '')
                {
                    if(IP_ADDRESS){
                        $sIP = IP_ADDRESS;
                    }
                }
                
                //Check for Port Number constant
                if($sPort == '')
                {   
                    if(PORT_NO){
                        $sPort = PORT_NO;
                    }
                }

                if($sIP == '' || $sPort == '')
                {
                    $aViewParameter['err_sucess']    =   '1';
                }
                else
                {
                
                    $this->home_model->updateSetting($sIP,$sPort);
                    
                    $this->home_model->updateMode($iMode);

                    $sResponse      =   get_rlb_status();
                    $sValves        =   $sResponse['valves'];
                    $sRelays        =   $sResponse['relay'];
                    $sPowercenter   =   $sResponse['powercenter'];

                    if($iMode == 3 || $iMode == 1)
                    { //1-auto, 2-manual, 3-timeout
                        //off all relays
                        if($sRelays != '')
                        {
                            $sRelayNewResp = str_replace('1','0',$sRelays);
                            onoff_rlb_relay($sRelayNewResp);
                        }
                        
                        //off all valves
                        if($sValves != '')
                        {
                            $sValveNewResp = str_replace(array('1','2'), '0', $sValves);
                            onoff_rlb_valve($sValveNewResp);  
                        }
                        
                        //off all power center
                        if($sPowercenter != '')
                        {
                            $sPowerNewResp = str_replace('1','0',$sPowercenter);  
                            onoff_rlb_powercenter($sPowerNewResp); 
                        }

                    }
                     $aViewParameter['sucess']    =   '1';
                }

                
               
            }
            
            $aModes =   $this->home_model->getAllModes();
            $sSelectModeOpt =   '<select name="relay_mode" id="relay_mode" class="form-control"><option value="0" >Please Select Mode</option>';
            foreach($aModes as $iMode)
            {
                $sSelectModeOpt .= '<option value="'.$iMode->mode_id.'"';
                                if($iMode->mode_status == '1'){
                                    $sSelectModeOpt .= ' selected="selected" ';
                                }
                                $sSelectModeOpt .= '>'.$iMode->mode_name.'</option>';
            }
            $sSelectModeOpt .= '<select>';
            $aViewParameter['sAllModes'] =$sSelectModeOpt;

            list($aViewParameter['sIP'],$aViewParameter['sPort']) = $this->home_model->getSettings();

            $this->load->view('Setting',$aViewParameter);
        }
        else
        {
            $this->checkSettingsSaved();

            $sResponse      =   get_rlb_status();
            //$sResponse      =   array('valves'=>'','powercenter'=>'0000','time'=>'','relay'=>'0000');
            $sValves        =   $sResponse['valves'];
            $sRelays        =   $sResponse['relay'];
            $sPowercenter   =   $sResponse['powercenter'];
            $sTime          =   $sResponse['time'];

            $aViewParameter['relay_count']  =   strlen($sRelays);
            $aViewParameter['valve_count']  =   strlen($sValves);
            $aViewParameter['power_count']  =   strlen($sPowercenter);
            $aViewParameter['time']         =   $sTime;

            $aViewParameter['sRelays']        =   $sRelays; 
            $aViewParameter['sPowercenter']   =   $sPowercenter;
            $aViewParameter['sValves']        =   $sValves;

            $aViewParameter['sDevice']        =   $sPage;
            /*if($sPage == 'relay')
               $aViewParameter['sDevice']        =   'R'; 
            else if($sPage == 'power')
               $aViewParameter['sDevice']        =   'P'; 
            else if($sPage == 'valve')
               $aViewParameter['sDevice']        =   'V';  */

            $this->load->view('Device',$aViewParameter); 
        }
    }

    public function updateStatusOnOff()
    {
        $sResponse      =   get_rlb_status();
        //$sResponse      =   array('valves'=>'0120','powercenter'=>'0000','time'=>'','relay'=>'0000');
        $sValves        =   $sResponse['valves'];
        $sRelays        =   $sResponse['relay'];
        $sPowercenter   =   $sResponse['powercenter'];
        $sTime          =   $sResponse['time'];

        $sName          =   $this->input->post('sName');
        $sStatus        =   $this->input->post('sStatus');
        $sDevice        =   $this->input->post('sDevice');

        $sNewResp       =   '';

        if($sDevice == 'R')
        {
            $sNewResp = replace_return($sRelays, $sStatus, $sName );
            onoff_rlb_relay($sNewResp);
        }
        if($sDevice == 'P')
        {
            $sNewResp = replace_return($sPowercenter, $sStatus, $sName );
            onoff_rlb_powercenter($sNewResp);
        }
        if($sDevice == 'V')
        {
            //echo $sStatus;
            $sNewResp = replace_return($sValves, $sStatus, $sName );
            onoff_rlb_valve($sNewResp);
        }

        exit;
    }

    public function deviceName()
    {
       
        $aViewParameter['page']      =   'home';
        $aViewParameter['sucess']    =   '0';
        $sDeviceID  =   base64_decode($this->uri->segment('3'));
        $sDevice    =   base64_decode($this->uri->segment('4'));

        if($sDeviceID == '')   
        {
            $sDeviceID  =   base64_decode($this->input->post('sDeviceID'));
            if($sDeviceID == '')
                if($sDevice != '')
                redirect(site_url('home/setting/'.$sDevice));
        }

        if($sDevice == '')   
        {
            $sDevice  =   base64_decode($this->input->post('sDevice'));
            if($sDevice == '')
                redirect(site_url('home'));
        }

        $aViewParameter['sDeviceID']    =   $sDeviceID;
        $aViewParameter['sDevice']      =   $sDevice;

        $this->load->model('home_model');

        if($this->input->post('command') == 'Save')
        {
            $sDeviceName = $this->input->post('sDeviceName');
            $this->home_model->saveDeviceName($sDeviceID,$sDevice,$sDeviceName);

            $aViewParameter['sucess']    =   '1';
        }

        $aViewParameter['sDeviceName']      =   $this->home_model->getDeviceName($sDeviceID,$sDevice);

        $this->load->view('DeviceName',$aViewParameter); 
    }

    public function setPrograms()
    {
        $aViewParameter['page']      =   'home';
        $aViewParameter['sucess']    =   '0';
        $sDeviceID      =   base64_decode($this->uri->segment('3'));
        $sProgramID     =   base64_decode($this->uri->segment('4'));
        $sProgramDelete =   $this->uri->segment('5');

        $this->load->model('home_model');

        if($sDeviceID == '')   
        {
            $sDeviceID  =   base64_decode($this->input->post('sDeviceID'));
            if($sDeviceID == '')
                redirect(site_url('home/setting/R'));
        }
        
        $aViewParameter['sDeviceID']    =   $sDeviceID;
        
        if($this->input->post('command') == 'Save')
        {
            if($this->input->post('sRelayNumber') != '')
                $sDeviceID   =  $this->input->post('sRelayNumber');

            $this->home_model->saveProgramDetails($this->input->post(),$sDeviceID);
            $aViewParameter['sucess']    =   '1';
        }

        if($this->input->post('command') == 'Update')
        {
            if($sProgramID == '')
            {
                $sProgramID  =   base64_decode($this->input->post('sProgramID'));
                if($sProgramID == '')
                    redirect(site_url('home/setPrograms/'.base64_encode($sDeviceID)));
            }  

            if($this->input->post('sRelayNumber') != '')
                $sDeviceID   =  $this->input->post('sRelayNumber'); 

            $this->home_model->updateProgramDetails($this->input->post(),$sProgramID,$sDeviceID);
            redirect(site_url('home/setPrograms/'.base64_encode($sDeviceID)));
        }

        if($sProgramDelete != '' && $sProgramDelete == 'D')
        {
            if($sProgramID == '')
            {
                $sProgramID  =   base64_decode($this->input->post('sProgramID'));
                if($sProgramID == '')
                    redirect(site_url('home/setPrograms/'.base64_encode($sDeviceID)));
            }

            $this->home_model->deleteProgramDetails($sProgramID);
            redirect(site_url('home/setPrograms/'.base64_encode($sDeviceID)));
        }

        $aViewParameter['sProgramDetails'] = $this->home_model->getProgramDetailsForDevice($sDeviceID);

        if($sProgramID != '')
        {
            $aViewParameter['sProgramID'] = $sProgramID;
            $aViewParameter['sProgramDetailsEdit'] = $this->home_model->getProgramDetails($sProgramID);
        }
        else
        {
            $aViewParameter['sProgramID']          = ''; 
            $aViewParameter['sProgramDetailsEdit'] = '';
        }
        $this->load->view('Programs',$aViewParameter); 
    }

    public function program()
    {
        $this->load->model('home_model');
        $sResponse      =   get_rlb_status();
        //$sResponse      =   array('valves'=>'','powercenter'=>'0000','time'=>'','relay'=>'0000','day'=>'');
        $sValves        =   $sResponse['valves'];
        $sRelays        =   $sResponse['relay'];
        $sPowercenter   =   $sResponse['powercenter'];
        $sTime          =   $sResponse['time'];
        $sDayret        =   $sResponse['day'];
        $aTime          =   explode(':',$sTime);

        $iRelayCount    =   strlen($sRelays);
        $iValveCount    =   strlen($sValves);
        $iPowerCount    =   strlen($sPowercenter);

        $iMode          =   $this->home_model->getActiveMode();
        //$iMode          =   $this->uri->segment('3');
        //$sTime          =   date('H:i:s',time());
        $aAllProgram    =   $this->home_model->getAllProgramsDetails();
        
        // die;
        if(is_array($aAllProgram) && !empty($aAllProgram))
        {
            foreach($aAllProgram as $aResultProgram)
            {
                $sRelayName     = $aResultProgram->relay_number;
                $iProgId        = $aResultProgram->relay_prog_id;
                $sProgramType   = $aResultProgram->relay_prog_type;
                $sProgramStart  = $aResultProgram->relay_start_time;
                $sProgramEnd    = $aResultProgram->relay_end_time;
                $sProgramActive = $aResultProgram->relay_prog_active;
                $sProgramDays   = $aResultProgram->relay_prog_days;
                
                $sProgramAbs            = $aResultProgram->relay_prog_absolute;
                $sProgramAbsStart       = $aResultProgram->relay_prog_absolute_start_time;
                $sProgramAbsEnd         = $aResultProgram->relay_prog_absolute_end_time;
                $sProgramAbsTotal       = $aResultProgram->relay_prog_absolute_total_time;
                $sProgramAbsAlreadyRun  = $aResultProgram->relay_prog_absolute_run_time;

                $sProgramAbsStartDay    = $aResultProgram->relay_prog_absolute_start_date;
                $sProgramAbsRun         = $aResultProgram->relay_prog_absolute_run;

                $sDays          =   '';
                $aDays          =   array();

                if($sProgramType == 2)
                {
                    $sDays = str_replace('7','0', $sProgramDays);
                    $aDays = explode(',',$sProgramDays);
                }

                if($sProgramType == 1 || ($sProgramType == 2 && in_array($sDayret, $aDays)))
                {
                    $aAbsoluteDetails       = array('absolute_s'  => $sProgramAbsStart,
                                                        'absolute_e'  => $sProgramAbsEnd,
                                                        'absolute_t'  => $sProgramAbsTotal,
                                                        'absolute_ar' => $sProgramAbsAlreadyRun,
                                                        'absolute_sd' => $sProgramAbsStartDay,
                                                        'absolute_st' => $sProgramAbsRun
                                                        ); 

                    if($sProgramAbs == '1' && $iMode == 1)
                    {
                        if($sProgramActive == 0)
                            $this->home_model->updateProgramAbsDetails($iProgId, $aAbsoluteDetails);
                        
                        if($sTime >= $sProgramStart && $sProgramActive == 0 && $sProgramAbsRun == 0)
                        {
                            $iRelayStatus = 1;
                            $sRelayNewResp = replace_return($sRelays, $iRelayStatus, $sRelayName );
                            onoff_rlb_relay($sRelayNewResp);
                            $this->home_model->updateProgramStatus($iProgId, 1);
                        }
                        else if($sTime >= $sProgramAbsEnd && $sProgramActive == 1)
                        {
                            $iRelayStatus = 0;
                            $sRelayNewResp = replace_return($sRelays, $iRelayStatus, $sRelayName );
                            onoff_rlb_relay($sRelayNewResp);
                            $this->home_model->updateProgramStatus($iProgId, 0);
                            $this->home_model->updateAbsProgramRun($iProgId, '1');
                        }
                    }
                    else if($sProgramAbs == '1' && $iMode == 2)
                    {
                        if($sProgramActive == 1)
                        {
                            $iRelayStatus = 0;
                            $sRelayNewResp = replace_return($sRelays, $iRelayStatus, $sRelayName );
                            onoff_rlb_relay($sRelayNewResp);
                            $this->home_model->updateProgramStatus($iProgId, 0);
                            $this->home_model->updateAlreadyRunTime($iProgId, $aAbsoluteDetails);
                        }
                    }
                    else
                    {
                        //on relay
                        if($sTime >= $sProgramStart && $sTime < $sProgramEnd && $sProgramActive == 0)
                        {
                            if($iMode == 1)
                            {
                                $iRelayStatus = 1;
                                $sRelayNewResp = replace_return($sRelays, $iRelayStatus, $sRelayName );
                                onoff_rlb_relay($sRelayNewResp);
                                $this->home_model->updateProgramStatus($iProgId, 1);
                            }
                        }//off relay
                        else if($sTime >= $sProgramEnd && $sProgramActive == 1)
                        {
                            $iRelayStatus = 0;
                            $sRelayNewResp = replace_return($sRelays, $iRelayStatus, $sRelayName );
                            onoff_rlb_relay($sRelayNewResp);
                            $this->home_model->updateProgramStatus($iProgId, 0);
                        }
                    } 
               }
            }
        }

    }

    public function checkSettingsSaved()
    {
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
            redirect(site_url('home/setting/'));
    }

    public function systemStatus()
    {
        $aViewParameter['page'] ='status';
        
        $this->checkSettingsSaved();
        $sResponse      =   get_rlb_status();

        $aViewParameter['response'] =$sResponse['response'];
         
        $this->load->view('Status',$aViewParameter);
    }
    
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */