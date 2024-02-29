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
		
		
		
		
		
		$(function(){  
            $('#ttd1').combogrid({  
                panelWidth:400,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rka/load_tanda_tangan',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]]  
            });          
         });
		 
		 
		 
		
		 
		 
		 $(function(){  
            $('#ttd2').combogrid({  
                panelWidth:400,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/rka/load_tanda_tangan2',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]]  
            });          
         });
		
		
		

		});	

		
		
		
		


 function openWindow( url ){
         //var ckdskpd = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
         //var ckdskpd = cnoskpd.split("/").join("123456789");
           var  ctglttd = $('#tgl_ttd').datebox('getValue');
		   var  ttd = $('#ttd1').combogrid('getValue');
		   var  ttd_2 = $('#ttd2').combogrid('getValue');
           var ttd1 = ttd.split(" ").join("a");
		   var ttd2 = ttd_2.split(" ").join("a");
		   var atas   =  document.getElementById('atas').value;
		   var bawah   =  document.getElementById('bawah').value;
           var kiri   =  document.getElementById('kiri').value;
		   var kanan   =  document.getElementById('kanan').value;

		   if (ttd=='' || ctglttd==''){
		   alert("Penanda tangan 1 atau tanggal Tanda tangan tidak boleh kosong");
		   } else {
            l1 = '/'+atas+'/'+bawah+'/'+kiri+'/'+kanan;
		   lc = '?tgl_ttd='+ctglttd+'&ttd1='+ttd1+'&ttd2='+ttd2;
			window.open(url+l1+lc,'_blank');
			window.focus();
			}
		  
     } 
	 
	 function alltrim(kata){
	 //alert(kata);
		b = (kata.split(' ').join(''));
		c = (b.replace( /\s/g, ""));
		return c
	 
	 }
	 

  
   </script>

	<div id="content">        
    	<h1><?php echo $page_title; ?><span><a href="<?php echo site_url(); ?>/rka/rka221">Kembali</a></span></h1>
       
        
        <?php echo form_close(); ?>   
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
    
		
		<tr>
		<td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">Penanda Tangan 1</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="ttd1" style="width: 100px;" /> 
                            </td> 
							
							<td width="20%">Penanda Tangan 2</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="ttd2" style="width: 100px;" /> 
                            </td> 
                        </table>
                </div>
        </td> 
		</tr>
		<tr>
		<td colspan="3">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">TANGGAL TTD</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="tgl_ttd" style="width: 100px;" /> 
                            </td> 
                        </table>
                </div>
        </td> 
		</tr>
     		<tr >
    			<td colspan='2'width="100%" height="40" ><strong>Ukuran Margin Untuk Cetakan PDF (Milimeter)</strong></td>
    		</tr>
			<tr>
    			<td colspan='2'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    			Kiri  : &nbsp;<input type="number" id="kiri" name="kiri" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
    			Kanan : &nbsp;<input type="number" id="kanan" name="kanan" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
    			Atas  : &nbsp;<input type="number" id="atas" name="atas" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
    			Bawah : &nbsp;<input type="number" id="bawah" name="bawah" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
    			</td>
    		</tr>
			<tr>
				<td>
				</td>
			</tr>
  	

		
        <table class="narrow">
        	<tr>
                <th>Kode Kegiatan</th>
                <th>Nama Kegiatan</th>
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $kegiatan) : ?>
            <tr>
                <td><?php echo $kegiatan->giat; ?></td>
                <td><?php echo $kegiatan->nm_kegiatan;?></td>
				<td>
				<a href="<?php echo site_url(); ?>/rka/preview_rka221/<?php echo $kegiatan->kd_skpd; ?>/<?php echo $kegiatan->giat; ?>/<?php echo '0';?> "class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false"><img src="<?php echo base_url(); ?>assets/images/icon/print.png"  width="25" height="23" title="cetak"></a> 
				<a href="<?php echo site_url(); ?>/rka/preview_rka221/<?php echo $kegiatan->kd_skpd; ?>/<?php echo $kegiatan->giat; ?>/<?php echo '1';?> "class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false"><img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png"  width="25" height="23" title="cetak"></a>
				<a href="<?php echo site_url(); ?>/rka/preview_rka221/<?php echo $kegiatan->kd_skpd; ?>/<?php echo $kegiatan->giat; ?>/<?php echo '2';?> "class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false"><img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg"  width="25" height="23" title="cetak"></a>
				<a href="<?php echo site_url(); ?>/rka/preview_rka221/<?php echo $kegiatan->kd_skpd; ?>/<?php echo $kegiatan->giat; ?>/<?php echo '3';?> "class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false"><img src="<?php echo base_url(); ?>assets/images/icon/word.jpg"  width="25" height="23" title="cetak"></a>
				</td>
				</tr>
            <?php endforeach; ?>
        </table>
		
		<table> 
		<tr align="center">
               <center>
               <!-- <a href="<?php echo site_url(); ?>/rka/preview_rka221_all/<?php echo $kegiatan->kd_skpd; ?>/<?php echo '1';?>"  class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:openWindow(this.href);return false">Cetak Kelesuruhan</a></center>
                --></td>                
            </tr>
		
		</table>
        <div class="clear"></div>
	</div>