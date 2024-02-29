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
		 
	
		

		});	

		
		
		
		


 function openWindow( url ){
         //var ckdskpd = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
         //var ckdskpd = cnoskpd.split("/").join("123456789");
           var  ctglttd = $('#tgl_ttd').datebox('getValue');
		   var  ttd = $('#ttd1').combogrid('getValue');
		   var ttd1 = alltrim(ttd)
		   if (ttd=='' || ctglttd==''){
		   alert("Penanda tangan  atau tanggal Tanda tangan tidak boleh kosong");
		   } else {
            lc = '?tgl_ttd='+ctglttd+'&ttd1='+ttd1+'';
			window.open(url+lc,'_blank');
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
    	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp; 
       </h1>
        
		
		<?php if (  $this->session->flashdata('notify') <> "" ) : ?>
        <div class="success"><?php echo $this->session->flashdata('notify'); ?></div>
        <?php endif; ?>
		
		<tr>
		<td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">Penanda Tangan</td>
                            <td width="1%">:</td>
                            <td><input type="text" id="ttd1" style="width: 100px;" /> 
                            </td> 
								<td width="20%">TANGGAL TTD</td>
								<td width="1%">:</td>
								<td><input type="text" id="tgl_ttd" style="width: 100px;" /> 
							</td> 
                        </table>
                </div>
        </td> 
		</tr>
		
        <table class="narrow">
        	<tr>
 	            <th>Kode SKPD </th>            	
                <th>Nama SKPD</th>                
                <th>Aksi</th>
            </tr>
            <?php foreach($list->result() as $skpd) : ?>
            <tr>
                <td><?php echo $skpd->kd_skpd; ?></td>            	
                <td><?php echo $skpd->nm_skpd; ?></td>  
                <td>                     
					<a href="<?php echo site_url(); ?>/rka/preview_dppa1/<?php echo $skpd->kd_skpd; ?>/<?php echo '1';?> "class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false"><img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png"  width="25" height="23" title="cetak"></a>				
					<a href="<?php echo site_url(); ?>/rka/preview_dppa1/<?php echo $skpd->kd_skpd; ?>/<?php echo '2';?> "class="easyui-linkbutton" plain="true" onclick="javascript:openWindow(this.href);return false"><img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg"  width="25" height="23" title="cetak"></a>				
            </tr>
            <?php endforeach; ?>
        </table>
        <div class="clear"></div>
	</div>