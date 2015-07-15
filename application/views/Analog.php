<?php
$this->load->view('Header');
?>
    <div id="page-wrapper">

        <div class="row">
          <div class="col-lg-12">
            <ol class="breadcrumb">
              <li class="active"><i class="fa fa-dashboard"></i> <a href="<?php echo site_url();?>" style="color:#333;">Dashboard</a> >> Analog Input</li>
            </ol>
            <?php if($sucess == '1') { ?>
              <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Details saved successfully! 
              </div>
            <?php } ?>
          </div>
        </div><!-- /.row -->
        <div class="row">
          <div class="col-lg-12">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Assign Device To Analog Input</h3>
              </div>
              <div class="panel-body">
                <div id="morris-chart-area">
                <form action="<?php echo site_url('analog/');?>" method="post">
                <table class="table table-hover">
                <thead>
                  <tr>
                    <th class="header">Analog Input</th>
                    <th class="header">&nbsp;</th>
                    <th class="header">Device</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $d=0;
                        foreach($aResponse as $input => $status)  
                        {

                            $sSelectDevice  =   '<select name="sDeviceName[]" id="sDeviceName" class="form-control">';
                            $sSelectDevice  .=  '<option value="">---- Select Device ----</option>';

                            if($sRelays != '')
                            {
                                for ($i=0;$i < $relay_count; $i++)
                                {
                                    $sDeviceNameDb = 'Relay '.$i;
                                    $sNameDb =  $this->home_model->getDeviceName($i,'R');

                                    if($sNameDb != '')
                                        $sDeviceNameDb  .= ' ('.$sNameDb.')';

                                    $sSelect = '';
                                    if(!empty($aAllAnalogDevice) && $aAllAnalogDevice[$d] == $i.'_R')
                                      $sSelect = 'selected="selected"';

                                        
                                    $sSelectDevice  .='<option value="'.$i.'_R" '.$sSelect.'>'.$sDeviceNameDb.'</option>';
                                }
                            }

                            if($sValves != '')
                            {
                                for ($i=0;$i < $valve_count; $i++)
                                {
                                    
                                    $sDeviceNameDb = 'Valve '.$i;
                                    $sNameDb =  $this->home_model->getDeviceName($i,'V');

                                    if($sNameDb != '')
                                        $sDeviceNameDb  .= ' ('.$sNameDb.')';

                                    $sSelect = '';
                                    if(!empty($aAllAnalogDevice) && $aAllAnalogDevice[$d] == $i.'_V')
                                      $sSelect = 'selected="selected"';  
                                      
                                    $sSelectDevice  .='<option value="'.$i.'_V" '.$sSelect.'>'.$sDeviceNameDb.'</option>';
                                }
                            }

                            if($sPowercenter != '')
                            {
                                for ($i=0;$i < $power_count; $i++)
                                {
                                    
                                    $sDeviceNameDb = 'Power Center '.$i;
                                    $sNameDb =  $this->home_model->getDeviceName($i,'P');

                                    if($sNameDb != '')
                                        $sDeviceNameDb  .= ' ('.$sNameDb.')';

                                    $sSelect = '';
                                    if(!empty($aAllAnalogDevice) && $aAllAnalogDevice[$d] == $i.'_P')
                                      $sSelect = 'selected="selected"';   
                                      
                                    $sSelectDevice  .='<option value="'.$i.'_P" '.$sSelect.'>'.$sDeviceNameDb.'</option>';
                                }
                            }

                            $sSelectDevice  .='</select>';


                            echo '<tr>
                                  <td>'.$input.'</td>
                                  <td>&nbsp;</td>
                                  <td>'.$sSelectDevice.'</td>
                                  </tr>';
                          $d++;        
                        }
                  ?>     

                  <tr><td colspan="3"><input type="submit" name="command" value="Save" class="btn btn-success" ></td></tr> 
                </tbody>
                </table>
                </form>      
                </div>
              </div>
            </div>
          </div>
        </div><!-- /.row -->
      </div><!-- /#page-wrapper -->
<script type="text/javascript">
</script>
<hr>
<?php
$this->load->view('Footer');
?>