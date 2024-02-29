

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <script type="text/javascript">
    
    var kode = '';
    var giat = '';
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
    var ctk = '';
        
     $(document).ready(function() {
            
        get_skpd();
        });
    
     $(function(){ 
        
       $("#div_bulan").hide();
       $("#div_periode").hide(); 
      
       
        
      
    });        


	   
	 
    
    
   
	
	function cek(cetak){
	if(cetak==1){
	url="<?php echo site_url(); ?>/cek_data/cek_spp";
	}
	if(cetak==2){
	url="<?php echo site_url(); ?>/cek_data/cek_spm";
	}
	if(cetak==3){
	url="<?php echo site_url(); ?>/cek_data/cek_trans";
	}
	if(cetak==4){
	url="<?php echo site_url(); ?>/cek_data/cek_rek";
	}
	if(cetak==5){
	url="<?php echo site_url(); ?>/cek_data/list_skpd";
	}
	if(cetak==6){
	url="<?php echo site_url(); ?>/cek_data/cek_sp2d";
	}
	window.open(url,'_blank');
         window.focus()
	}
	
  
   </script>


<div id="content1" align="center"> 
    <h3 align="center"><b>CEK DATA</b></h3>
    <fieldset style="width: 70%;">
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td colspan="3" align="left">
				<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cek(1)">Cek SPP</a>
                </td>                
            </tr>
        <tr>
                <td colspan="3" align="left">
				<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cek(2)">Cek SPM</a>
                </td>                
            </tr>
			<tr>
                <td colspan="3" align="left">
				<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cek(3)">Cek Transaksi</a>
                </td>                
            </tr>
			<tr>
                <td colspan="3" align="left">
				<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cek(4)">Cek Rek Trans</a>
                </td>                
            </tr>
			<tr>
                <td colspan="3" align="left">
				<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cek(6)">Cek SP2D</a>
                </td>                
            </tr>
			<tr>
                <td colspan="3" align="left">
				<a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cek(5)">List Nama SKPD</a>
                </td>                
            </tr>
			
		</table>  
            
    </fieldset>  
</div>	
