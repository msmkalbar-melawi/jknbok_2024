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


    $(function(){
        $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
            	var y = date.getFullYear();
            	var m = date.getMonth()+1;
            	var d = date.getDate();
            	return y+'-'+m+'-'+d;
            }
        });
        $('#ttd2').combogrid({  
            panelWidth:400,  
            idField:'nip',  
            textField:'nama',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/load_ttd_gub',  
            columns:[[  
                {field:'nip',title:'NIP',width:200},  
                {field:'nama',title:'Nama',width:400}    
            ]]  
        });          
    });

        function cek($cetak,$jns){
            var ckdskpd = $jns;        
            var atas   =  document.getElementById('atas').value;
            var bawah   =  document.getElementById('bawah').value;
            var kanan   =  document.getElementById('kanan').value;
            var kiri   =  document.getElementById('kiri').value;
    
            url="<?php echo site_url(); ?>/rka/preview_perdaIV_sempurna/"+ckdskpd+'/'+$cetak+'/'+atas+'/'+bawah+'/'+kiri+'/'+kanan+'/Lampiran_IV_Penyempurnaan_'+ckdskpd;
    
            openWindow( url,$jns );
        }

        function openWindow( url,$jns ){
            var  ctglttd = $('#tgl_ttd').datebox('getValue');
            var ttd_2 = $('#ttd2').combogrid('getValue');
            var ttd2 = ttd_2.split(" ").join("a");
        
            if (ctglttd == ''){ 
                alert("Tanggal Tidak Boleh Kosong"); 
                return;
            }

            if (ttd2 == ''){ 
                alert("Penandatangan Tidak Boleh Kosong"); 
                return;
            }

            lc = '?tgl_ttd='+ctglttd+'&ttd2='+ttd2+'';   
            window.open(url+lc,'_blank');
			window.focus();        		  
         } 

    </script>

	<div id="content">      
    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; 
        </h1>
 
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
        <table style="width:100%;" border="0">    
      		<tr>
                        <td width="15%">TANGGAL TTD</td>
                        <td width="1%">:</td>
                        <td><input type="text" id="tgl_ttd" style="width: 100px;" /> 
                        </td> 
                   
            </tr> 
            <tr>
                <td >GUB / SEKDA</td>         
                <td >:</td>            
                <td ><input type="text" id="ttd2" style="width: 200px;" /> 
                </td> 
            </tr>
            <tr >
                <td colspan='3'width="100%" style="border: none;"><strong>Ukuran Margin Untuk Cetakan PDF (Milimeter)</strong></td>
            </tr>
            <tr >
                <td colspan='3'  style="border: none;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Kiri  : &nbsp;<input type="number" id="kiri" name="kiri" style="width: 50px; border:1" value="10" /> &nbsp;&nbsp;
                Kanan : &nbsp;<input type="number" id="kanan" name="kanan" style="width: 50px; border:1" value="10" /> &nbsp;&nbsp;
                Atas  : &nbsp;<input type="number" id="atas" name="atas" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
                Bawah : &nbsp;<input type="number" id="bawah" name="bawah" style="width: 50px; border:1" value="10" /> &nbsp;&nbsp;
                </td>
            </tr>

        </table>

       
        <table class="narrow">
        	<tr>
 	            <th>Pilihan </th>            	
                <th>Aksi</th>
            </tr>
            <tr>                
            <tr>                
                <td><?php echo 'SKPD'; ?></td>            	

                <td >                     
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'SKPD');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'SKPD');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(2,'SKPD');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a>

                </td>    


            </tr>
            <tr>                
                <td><?php echo 'UNIT'; ?></td>            	
                <td >                     
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0,'UNIT');return false" >
                    <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="preview"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1,'UNIT');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                    <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(2,'UNIT');return false">                    
                    <img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg" width="25" height="23" title="cetak"/></a>

                </td>    

            </tr>
        </table>
 
        <div class="clear"></div>
	</div>