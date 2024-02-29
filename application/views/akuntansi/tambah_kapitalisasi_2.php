<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css" />
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/shortcut.js"></script>
   
    <script type="text/javascript">
 
	var nl       = 0;
	var tnl      = 0;
	var idx      = 0;
	var tidx     = 0;
	var oldRek   = 0;
    var rek      = 0;
    var detIndex = 0;
    var kdrek    = '';
    var id       = 0;
    var status   = '0';
    var zfrek    = '';
    var zkdrek   = '';
    var status_apbd = '';
    var total_kas   = 0;
    shortcut.add("ctrl+m", function() {
        detsimpan();
    });
    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 450,
                width: 970,
                modal: true,
                autoOpen:false                
            });
            
            $("#dialog-modal-edit" ).dialog({
                height: 230,
                width: 970,
                modal: true,
                autoOpen:false                
            });
            get_skpd();
			$("#loading").dialog({
				resizable: false,
				width:200,
				height:130,
				modal: true,
				draggable:false,
				autoOpen:false,    
				closeOnEscape:false
				});
			
        });    
    
    $(document).ready(function(){
       // $('#skpd').hide();
       // $('#giat').hide();
    });
    
	
    $(function(){
             
           var mgiat = document.getElementById('kdgiat').value; 
	       $('#dg').edatagrid({
				url           : '<?php echo base_url(); ?>/index.php/akuntansi/select_kapitalisasi',
                 idField      : 'id',
                 toolbar      : "#toolbar",              
                 rownumbers   : "true", 
                 fitColumns   : "true",
                 singleSelect : "true",
			 	onSelect:function(rowIndex,rowData){							
    				},
				columns:[[
	                {field:'id',
					 title:'id',
					 width:10,
                     hidden:true
					},
					{field:'kd_kegiatan',
					 title:'Kegiatan',
					 width:12,
					 align:'left'	
					},
					{field:'kd_rek5',
					 title:'Rekening',
					 width:78
					},
                    {field:'nm_rek5',
					 title:'Nama Rekening',
					 width:30,
                     align:'right'
                     },
					 {field:'nil_ang',
					 title:'Anggaran',
					 width:30,
                     align:'right'
                     },
					 {field:'kapitalisasi',
					 title:'Kapitalisasi',
					 width:30,
                     align:'right'
                     },
					 {field:'jenis',
					 title:'Jenis',
					 width:30,
                     align:'right'
                     },
					 {field:'rinci',
					  title:'Detail',
					  width:10,
					  align:'center', 
					  formatter:function(value,rec){
							rek         = rec.kd_rek5
							return ' <p onclick="javascript:section('+rec.kd_rek5+');">Rincian</p>';
						}
			 		}
                    
				]]
			});
			});
             
            /* 
            $(function(){
            $('#rek5').combogrid({  
            panelWidth : 700,  
            idField    : 'kd_rek5',  
            textField  : 'kd_rek5',  
            mode       : 'remote',
            url        : '<?php echo base_url(); ?>index.php/rka/ambil_rekening5',  
            columns    : [[  
                {field:'kd_rek5',title:'Kode ',width:100},  
                {field:'nm_rek5',title:'Nama ',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                kdrek = rowData.kd_rek5;
                validate_rek();
            }  
            }); 
            });
            */
			$(function(){
				$('#rek3').combogrid({  
                panelWidth:200,  
                url: '<?php echo base_url(); ?>/index.php/tukd/load_rek3_lamp_aset_input',  
                    idField:'kd_rek3',  
                    textField:'kd_rek3',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_rek3',title:'Kode',width:30},
                           {field:'nm_rek3',title:'Nama Rekening 3',width:150} 
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    kdrek3 = rowData.kd_rek3;
                    $("#nm_rek3").attr("value",rowData.nm_rek3);
					$("#rek5").combogrid("setValue",'');
					$("#nm_rek5").attr("Value",'');
					$("#rek6").combogrid("setValue",'');
					$("#nm_rek6").attr("Value",'');
					$('#rek5').combogrid({url:'<?php echo base_url(); ?>/index.php/tukd/load_rek5_lamp_aset/'+kdrek3,
					});
					validate_input(kdrek3);
                    }   
                });
			});
			
			
			$(function(){
				$('#rek5').combogrid({  
                panelWidth:500,  
                //url: '<?php echo base_url(); ?>/index.php/tukd/load_rek3_lamp_aset',  
                    idField:'kd_rek5',  
                    textField:'kd_rek5',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_rek5',title:'Kode',width:100},
                           {field:'nm_rek5',title:'Nama Rekening',width:300} 
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    kdrek5 = rowData.kd_rek5;
                    $("#nm_rek5").attr("value",rowData.nm_rek5);
					$("#rek6").combogrid("setValue",'');
					$("#nm_rek6").attr("Value",'');
					$('#rek6').combogrid({url:'<?php echo base_url(); ?>/index.php/tukd/load_rek6_lamp_aset/'+kdrek5,});
                    }   
                });
			});
			
			$(function(){
				$('#rek6').combogrid({  
                panelWidth:500,  
                //url: '<?php echo base_url(); ?>/index.php/tukd/load_rek3_lamp_aset',  
                    idField:'kd_rek6',  
                    textField:'kd_rek6',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                           {field:'kd_rek6',title:'Kode',width:100},
                           {field:'nm_rek6',title:'Nama Rekening',width:300} 
                       ]],  
                    onSelect:function(rowIndex,rowData){
                    //kdrek3 = rowData.kd_rek3;
                    $("#nm_rek6").attr("value",rowData.nm_rek6);
					//runEffect(kdrek3);
                    }   
                });
			});
            
			  function validate_input(kdrek3){
		//var options = {}; 
		if (kdrek3==131){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',false);
			$("#lokasi0").attr('hidden',false);
			$("#alamat1").attr('hidden',false);
			$("#alamat0").attr('hidden',false);
			$("#sert1").attr('hidden',false);
			$("#sert0").attr('hidden',false);
			$("#luas1").attr('hidden',false);
			$("#luas0").attr('hidden',false);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
	
		} else if ((kdrek3==151) || (kdrek3==152) || (kdrek3==153) || (kdrek3==154)){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',false);
			$("#merk0").attr('hidden',false);
			$("#no_polisi1").attr('hidden',false);
			$("#no_polisi0").attr('hidden',false);
			$("#fungsi1").attr('hidden',false);
			$("#fungsi0").attr('hidden',false);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',false);
			$("#lokasi0").attr('hidden',false);
			$("#alamat1").attr('hidden',false);
			$("#alamat0").attr('hidden',false);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',false);
			$("#luas0").attr('hidden',false);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);

		}else if (kdrek3==141){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',false);
			$("#hukum0").attr('hidden',false);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
			
		}else if (kdrek3==136) {
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',false);
			$("#fungsi0").attr('hidden',false);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',false);
			$("#lokasi0").attr('hidden',false);
			$("#alamat1").attr('hidden',false);
			$("#alamat0").attr('hidden',false);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#luas1").attr('hidden',false);
			$("#luas0").attr('hidden',false);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
			
		} else if (kdrek3==135) {
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',false);
			$("#merk0").attr('hidden',false);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);	
			
		} else if ((kdrek3==134) || (kdrek3==133)) {
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',false);
			$("#fungsi0").attr('hidden',false);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',false);
			$("#lokasi0").attr('hidden',false);
			$("#alamat1").attr('hidden',false);
			$("#alamat0").attr('hidden',false);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#luas1").attr('hidden',false);
			$("#luas0").attr('hidden',false);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
		} else if(kdrek3==132){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',false);
			$("#merk0").attr('hidden',false);
			$("#no_polisi1").attr('hidden',false);
			$("#no_polisi0").attr('hidden',false);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
			} else if(kdrek3==313){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',true);
			$("#jumlah0").attr('hidden',true);
			$("#harga_satuan1").attr('hidden',true);
			$("#harga_satuan0").attr('hidden',true);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',true);
			$("#tahun_n0").attr('hidden',true);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',true);
			$("#keterangan0").attr('hidden',true);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',true);
			$("#harga_awal1").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
		}else if(kdrek3==117){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',false);
			$("#merk0").attr('hidden',false);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',false);
			$("#satuan0").attr('hidden',false);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
		} else if (kdrek3==116){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polis1").attr('hidden',false);
			$("#no_polis0").attr('hidden',false);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',false);
			$("#rincian_bebas0").attr('hidden',false);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',false);
			$("#kondisi_b0").attr('hidden',false);
			$("#kondisi_rb1").attr('hidden',false);
			$("#kondisi_rb0").attr('hidden',false);
			$("#kondisi_rr1").attr('hidden',false);
			$("#kondisi_rr0").attr('hidden',false);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',false);
			$("#no_polis").attr('hidden',false);

			
		} else if(kdrek3 ==114){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',false);
			$("#bulan_oleh0").attr('hidden',false);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',false);
			$("#piutang_awal0").attr('hidden',false);
			$("#piutang_koreksi1").attr('hidden',false);
			$("#piutang_koreksi0").attr('hidden',false);
			$("#piutang_sudah1").attr('hidden',false);
			$("#piutang_sudah0").attr('hidden',false);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);

			
		} else if (kdrek3 == 113){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',false);
			$("#bulan_oleh0").attr('hidden',false);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',false);
			$("#lokasi0").attr('hidden',false);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',false);
			$("#piutang_awal0").attr('hidden',false);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);

			
		} else if (kdrek3==111){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);

		} else if (kdrek3==112){
			$("#tahun_oleh1").attr('hidden',false);
			$("#tahun_oleh0").attr('hidden',false);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#milik1").attr('hidden',false);
			$("#milik0").attr('hidden',false);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',false);
			$("#hukum0").attr('hidden',false);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',false);
			$("#jumlah0").attr('hidden',false);
			$("#harga_satuan1").attr('hidden',false);
			$("#harga_satuan0").attr('hidden',false);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',false);
			$("#investasi_awal0").attr('hidden',false);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',false);
			$("#tahun_n0").attr('hidden',false);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',false);
			$("#keterangan0").attr('hidden',false);
			$("#harga_awal0").attr('hidden',false);
			$("#harga_awal1").attr('hidden',false);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);

		}
		else {
			alert("Belum ada form input");
			$("#tahun_oleh1").attr('hidden',true);
			$("#tahun_oleh0").attr('hidden',true);
			$("#bulan_oleh1").attr('hidden',true);
			$("#bulan_oleh0").attr('hidden',true);
			$("#merk1").attr('hidden',true);
			$("#merk0").attr('hidden',true);
			$("#milik1").attr('hidden',true);
			$("#milik0").attr('hidden',true);
			$("#no_polisi1").attr('hidden',true);
			$("#no_polisi0").attr('hidden',true);
			$("#no_polis1").attr('hidden',true);
			$("#no_polis0").attr('hidden',true);
			$("#fungsi1").attr('hidden',true);
			$("#fungsi0").attr('hidden',true);
			$("#hukum1").attr('hidden',true);
			$("#hukum0").attr('hidden',true);
			$("#lokasi1").attr('hidden',true);
			$("#lokasi0").attr('hidden',true);
			$("#alamat1").attr('hidden',true);
			$("#alamat0").attr('hidden',true);
			$("#sert1").attr('hidden',true);
			$("#sert0").attr('hidden',true);
			$("#luas1").attr('hidden',true);
			$("#luas0").attr('hidden',true);
			$("#satuan1").attr('hidden',true);
			$("#satuan0").attr('hidden',true);
			$("#jumlah1").attr('hidden',true);
			$("#jumlah0").attr('hidden',true);
			$("#harga_satuan1").attr('hidden',true);
			$("#harga_satuan0").attr('hidden',true);
			$("#rincian_bebas1").attr('hidden',true);
			$("#rincian_bebas0").attr('hidden',true);
			$("#piutang_awal1").attr('hidden',true);
			$("#piutang_awal0").attr('hidden',true);
			$("#piutang_koreksi1").attr('hidden',true);
			$("#piutang_koreksi0").attr('hidden',true);
			$("#piutang_sudah1").attr('hidden',true);
			$("#piutang_sudah0").attr('hidden',true);
			$("#investasi_awal1").attr('hidden',true);
			$("#investasi_awal0").attr('hidden',true);
			$("#sal_awal1").attr('hidden',true);
			$("#sal_awal0").attr('hidden',true);
			$("#kurang1").attr('hidden',true);
			$("#kurang0").attr('hidden',true);
			$("#tambah1").attr('hidden',true);
			$("#tambah0").attr('hidden',true);
			$("#tahun_n1").attr('hidden',true);
			$("#tahun_n0").attr('hidden',true);
			$("#akhir1").attr('hidden',true);
			$("#akhir0").attr('hidden',true);
			$("#kondisi_b1").attr('hidden',true);
			$("#kondisi_b0").attr('hidden',true);
			$("#kondisi_rb1").attr('hidden',true);
			$("#kondisi_rb0").attr('hidden',true);
			$("#kondisi_rr1").attr('hidden',true);
			$("#kondisi_rr0").attr('hidden',true);
			$("#keterangan1").attr('hidden',true);
			$("#keterangan0").attr('hidden',true);
			$("#harga_awal0").attr('hidden',true);
			$("#harga_awal1").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
			$("#no_polis").attr('hidden',true);
		}
	}
			
			
			
            $(function(){
            $("#sdana1").combogrid({
                panelWidth:300,
                idField   :'nm_sdana',
                textField :'nm_sdana',
                mode      :'remote',
                url       : '<?php echo base_url(); ?>index.php/rka/ambil_sdana',
                columns   : [[
                {field:'kd_sdana',title:'Kode',width:100},
                {field:'nm_sdana',title:'Sumber Dana',width:190}
                ]]
            });
            });
            
            
            $(function(){
            $("#sdana2").combogrid({
               panelWidth:300,
                idField   :'nm_sdana',
                textField :'nm_sdana',
                mode      :'remote',
                url       : '<?php echo base_url(); ?>index.php/rka/ambil_sdana',
                columns   : [[
                {field:'kd_sdana',title:'Kode',width:100},
                {field:'nm_sdana',title:'Sumber Dana',width:190}
                ]]
            });
            });
            
            
            $(function(){
            $("#sdana3").combogrid({
                panelWidth:300,
                idField   :'nm_sdana',
                textField :'nm_sdana',
                mode      :'remote',
                url       : '<?php echo base_url(); ?>index.php/rka/ambil_sdana',
                columns   : [[
                {field:'kd_sdana',title:'Kode',width:100},
                {field:'nm_sdana',title:'Sumber Dana',width:190}
                ]]
            });
            });
            
            
            $(function(){
            $("#sdana4").combogrid({
              panelWidth:300,
                idField   :'nm_sdana',
                textField :'nm_sdana',
                mode      :'remote',
                url       : '<?php echo base_url(); ?>index.php/rka/ambil_sdana',
                columns   : [[
                {field:'kd_sdana',title:'Kode',width:100},
                {field:'nm_sdana',title:'Sumber Dana',width:190}
                ]]
            });
            });


            
		//});
        
		function load_sum_rek_rka(c){                
		var a = document.getElementById('sskpd').value;
        var b = document.getElementById('kdgiat').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b,rek:c}),
            url:"<?php echo base_url(); ?>index.php/rka/load_sum_rek_rinci_rka",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal_rka").attr("value",n['rektotal_rka']);
                });
            }
         });
        });
    }
        
        
        function get_skpd()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#sskpd").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd.toUpperCase());
        								$("#nama_skpd").attr("value",data.nm_skpd.toUpperCase());
                                        $("#dn").attr("value",data.kd_skpd);
        								kdskpd = data.kd_skpd;
                                        //sta    = data.statu;
                                        validate_giat();
										//alert('asas');
                                        //tombol(sta);
                                        validate_rekening();
                                        $("#kdrek5").combogrid("disable");
        							  }                                     
        	});
        }
        
		
        
       
        
        
        
        function validate_giat(){
	  	    $(function(){
            $('#kdgiat').combogrid({  
            panelWidth : 700,  
            idField    : 'kd_kegiatan',  
            textField  : 'kd_kegiatan',  
            mode       : 'remote',
            url        : '<?php echo base_url(); ?>index.php/akuntansi/pgiat_kapit/'+kdskpd,  
            columns    : [[  
                {field:'kd_kegiatan',title:'Kode SKPD',width:150},  
                {field:'nm_kegiatan',title:'Nama Kegiatan',width:650}    
            ]],
            onSelect:function(rowIndex,rowData){
                kegiatan = rowData.kd_kegiatan;
                $("#nmgiat").attr("value",rowData.nm_kegiatan.toUpperCase());
                //$("#kdgiat").attr("value",rowData.kd_kegiatan);
                $("#jnskegi").attr("value",rowData.jns_kegiatan);
                validate_combo();
                $("#kdrek5").combogrid("disable");
                /*$("#kdrek5").combogrid("setValue",'');
                $("#sdana1").combogrid("setValue",'');
                $("#sdana2").combogrid("setValue",'');
                $("#sdana3").combogrid("setValue",'');
                $("#sdana4").combogrid("setValue",'');
                */
                //document.getElementById('nilairek').value   = 0;
                document.getElementById('nmrek5').value     = '';
            },
            }); 
            });
		}
        
            
        
        function validate_rekening(){
            
            $("#dg").datagrid("unselectAll");
            $("#dg").datagrid("selectAll");
            var rows   = $("#dg").datagrid("getSelections");
            var jrows  = rows.length ;

            zfrek  = '';
            zkdrek = '';
            
            for (z=0;z<jrows;z++){
               zkdrek=rows[z].kd_rek5;                 
               if ( z == 0 ){
                   zfrek  = zkdrek ;
               } else {
                   zfrek  = zfrek+','+zkdrek ;
               }
            }          
            
			var cgiat = $("#kdgiat").combogrid('getValue');
            var skpd = document.getElementById('sskpd').value ;

            $('#kdrek5').combogrid({  
               panelWidth : 500,  
               idField    : 'kd_rek5',  
               textField  : 'kd_rek5',  
               mode       : 'remote',
               url        : '<?php echo base_url(); ?>index.php/akuntansi/ambil_rekening5_all_ar',  
               queryParams: ({reknotin:zfrek,cgiat:cgiat,skpd:skpd}),
               columns    : [[  
                   {field:'kd_rek5',title:'Kode Rekening',width:100},  
                   {field:'nm_rek5',title:'Nama Rekening',width:400}    
               ]],  
               onSelect:function(rowIndex,rowData){
                    kd_rek5 = rowData.kd_rek5;
                    $("#nmrek5").attr("value",rowData.nm_rek5);
                    $("#anggaran").attr("value",rowData.anggaran);
                    $("#kapitalisasi").attr("value",rowData.kapitalisasi);
                    $("#transaksi").attr("value",rowData.transaksi);
                    $("#jenis").combobox("setValue",rowData.jenis);
               },
               onLoadSuccess:function(data){
                   // $("#nilairek").attr("value",0);
               }  
             });     
        }
        

		
        function getSelections(idx){
			var ids = [];
			var rows = $('#dg').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kd_rek5);
			}
			return ids.join(':');
		}

		
        function getSelections2(idx){
			var ids = [];
			var rows = $('#dg1').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].no_po);
			}
			return ids.join(':');
		}


		function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		}  


		function refresh(){  
			$('#dg').datagrid('reload');
		} 
		
		function refresh_rinci(){
		var kd_rek5 = $("#kdrek5").combogrid('getValue');
		section(kd_rek5);
		}

        function simpan(baru,lama,nilai,sdana){		
    		var cgiat = document.getElementById('kdgiat').value;
    		var cskpd = document.getElementById('sskpd').value;
            
    		if (lama==''){
    			lama=baru;
    		}
    			$(function(){
    				$('#dg').edatagrid({
    				     url: '<?php echo base_url(); ?>/index.php/rka/tsimpan/'+cskpd+'/'+cgiat+'/'+baru+'/'+lama+'/'+nilai+'/'+sdana,
    					 idField:'id',
    					 toolbar:"#toolbar",              
    					 rownumbers:"true", 
    					 fitColumns:"true",
    					 singleSelect:"true",
    				});
    			});
		}
        
                
        function validate_combo(){
			var cgiat = $("#kdgiat").combogrid('getValue');
            $(function(){
			$('#dg').edatagrid({
				 url: '<?php echo base_url(); ?>/index.php/akuntansi/select_kapitalisasi/'+cgiat,
                 idField     : 'id',
                 toolbar     : "#toolbar",              
                 rownumbers  : "true", 
                 fitColumns  : "true",
                 singleSelect: "true",
				 showFooter  : true,
				 nowrap      : false,
				 onSelect:function(rowIndex,rowData){							
							  
                              oldRek   = getSelections(getRowIndex(this));
                              vvkdrek  = rowData.kd_rek5;
                              vvnmrek  = rowData.nm_rek5;
                              vvkdkegi = rowData.kd_kegiatan;
                              vvnil_ang = rowData.nil_ang;
                              vvnil_trans = rowData.nil_trans;
                              vvkapit = rowData.kapitalisasi;
                              vvjenis = rowData.jenis;
                                
                             // $("#nilairek").attr("value",vvnilai);
                              $("#nmrek5").attr("value",vvnmrek);
                              $("#kdrek5").combogrid("setValue",vvkdrek);
                              $("#anggaran").attr("Value",vvnil_ang);
                              $("#kapitalisasi").attr("Value",vvkapit);
                              $("#transaksi").attr("Value",vvnil_trans);
                              $("#tot_trans").attr("Value",vvnil_trans);
                              $("#tot_kapit_rek").attr("Value",vvkapit);
                              $("#jenis").combobox("setValue",vvjenis);
                              
							load_sum_rek_rinci(vvkdrek);	
							load_sum_rek_kapit();	
                              
						  },
				onLoadSuccess:function(data){
						   //	load_sum_rek();		 
						  },
				columns:[[
	                {field:'id',
					 title:'id',
					 width:10,
                     hidden:true
					},
					{field:'kd_kegiatan',
					 title:'Kegiatan',
					 width:25,
					 align:'left'	
					},
					{field:'kd_rek5',
					 title:'Rekening',
					 width:10
					},
                    {field:'nm_rek5',
					 title:'Nama Rekening',
					 width:20,
                     align:'left'
                     },
					 {field:'nil_ang',
					 title:'Anggaran',
					 width:20,
                     align:'right'
                     },
					 {field:'kapitalisasi',
					 title:'Kapitalisasi',
					 width:20,
                     align:'right'
                     },
					 {field:'nil_trans',
					 title:'Transaksi',
					 width:20,
                     align:'right'
                     },
					 {field:'jenis',
					 title:'Jenis',
					 width:5,
                     align:'right'
                     },
					 {field:'rinci',
					  title:'Detail',
					  width:10,
					  align:'center', 
					  formatter:function(value,rec){
							rek         = rec.kd_rek5
							return ' <p onclick="javascript:section('+rec.kd_rek5+');">Rincian</p>';
						}
			 		}
                     /*
                     ,
                     {field:'hapus',title:'Hapus',width:10,align:"center",
                     formatter:function(value,rec){ 
                     return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus();" />';
                     }}
                     */
				]]

			});
		});
        }

        function buat_baru(){
		var del=confirm('Membuat baru akan menghapus data yang sudah ada | Anda yakin ?');
			if  (del==true){
			$(document).ready(function(){
            $.ajax({
                    type     : 'POST',
                    dataType : 'json',
                    url      : '<?php echo base_url(); ?>index.php/akuntansi/kapitalisasi_baru',
                    success  : function(data){
                               }    
                        });
                    });
			
			}
			
		}
        
        function hitung_kapitalisasi(){
			$(document).ready(function(){
            $.ajax({
                    type     : 'POST',
                    dataType : 'json',
                    url      : '<?php echo base_url(); ?>index.php/akuntansi/hitung_kapitalisasi',
                    success  : function(data){
						if (data = 1){
								$('#dg').datagrid('reload');
								alert('Penghitungan Selesai!');
							}
                          }    
                        });
                    });
					
		}
		
		function hitung_rincian_kapit(){
			var cgiat = $("#kdgiat").combogrid('getValue');
			var cskpd = document.getElementById('sskpd').value;
			var rek   = $("#kdrek5").combogrid('getValue');
			$(document).ready(function(){
            $.ajax({
                    type     : 'POST',
                    dataType : 'json',
                    data     : ({giat:cgiat,skpd:cskpd,rek:rek}),
                    url      : '<?php echo base_url(); ?>index.php/akuntansi/hitung_rincian_kapitalisasi',
                     beforeSend:function(xhr){
						$("#loading").dialog('open');
						},
					success  : function(data){
						if (data = 1){
								$('#dg').datagrid('reload');
								$("#loading").dialog('close');
								alert('Selesai!');

							} else{
								$("#loading").dialog('close');
								alert('Gagal!');
							}
                          }    
                        });
                    });
		}
		
		
		function transfer_lamp(){
			var kd_rek5 = $("#kdrek5").combogrid('getValue');
			$(document).ready(function(){
            $.ajax({
                    type     : 'POST',
                    dataType : 'json',
                    url      : '<?php echo base_url(); ?>index.php/akuntansi/proses_transfer_lamp',
                    beforeSend:function(xhr){
						$("#loading").dialog('open');
						},
					success  : function(data){
						if (data = 1){
								$('#dg').datagrid('reload');
								section(kd_rek5);
								$("#loading").dialog('close');
								alert('Transfer Selesai!');

							} else{
								$("#loading").dialog('close');
								alert('Transfer gagal!');
							}
                          }    
                        });
                    });
		}
		
        function validate_rek(){
            $(function(){
			$('#dg_rek').edatagrid({
				url          :  '<?php echo base_url(); ?>/index.php/rka/ld_rek/'+kegiatan+'/'+kdrek,
                idField      : 'id',                  
                rownumbers   : "true", 
                fitColumns   : "true",
                singleSelect : "true",
				showFooter   : true,
				nowrap       : false,				 
				columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_rek5',
					 title:'Kode Rekening',
					 width:20,
					 align:'left'
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:80
					}
				]],
                    onClickRow:function(rowIndex, rowData){                                
                    rk    = rowData.kd_rek5;
                    nmrk  = rowData.nm_rek5;
                    nilai = 0;
                    sdana = 'PAD';                    
                    simpan(rk,oldRek,nilai,sdana);   
					}				 		

			});
		});    
        }
        
        
		function hapus(){
				var cgiat = $("#kdgiat").combogrid('getValue');
				var cskpd = document.getElementById('sskpd').value;
				var ctot_rinci = angka(document.getElementById('tot_rinci').value);
				if(ctot_rinci==0){
					var rek   = getSelections();
					if (rek !=''){
					var del=confirm('Anda yakin akan menghapus rekening '+rek+' ?');
					if  (del==true){
						$(function(){
							$('#dg').edatagrid({
								 url: '<?php echo base_url(); ?>/index.php/akuntansi/thapus_kapitalisasi/'+cskpd+'/'+cgiat+'/'+rek,
								 idField:'id',
								 toolbar:"#toolbar",              
								 rownumbers:"true", 
								 fitColumns:"true",
								 singleSelect:"true"
							});
						});
						$("#kdrek5").combogrid("disable");
						$("#kdrek5").combogrid("setValue",'');
						document.getElementById('nmrek5').value = '';
						document.getElementById('anggaran').value = '';
						document.getElementById('kapitalisasi').value = '';
						$("#jenis").combobox("setValue",'');
						
						$("#dg").datagrid("unselectAll");
						zfrek  = '';
						zkdrek = '';
					
					}
					}
				}else{
					alert("Hapus dulu Rincian Lampiran Neraca!");
					exit();
				}
		}

		function hapus_rincian(){
				var cgiat = $("#kdgiat").combogrid('getValue');
				var cskpd = document.getElementById('sskpd').value;
				var rek   = $("#kdrek5").combogrid('getValue');
				var nolamp = document.getElementById('no_simpan').value;

				if (nolamp !=''){
				var del=confirm('Anda yakin akan menghapus lampiran '+nolamp+' ?');
				if  (del==true){
					 $(document).ready(function(){
                        $.ajax({
                               type     : 'POST',
                               dataType : 'json',
                               data     : ({giat:cgiat,skpd:cskpd,rek:rek,nolamp:nolamp}),
                               url      : '<?php echo base_url(); ?>index.php/akuntansi/thapus_rincian_kapit',
                               success  : function(data){
                                        st12=data;
                                        if ( st12=='0'){
                                            alert('Gagal Hapus...!!!');
                                        } else {
                                            alert("Data Telah Terhapus...!!!");
											section(rek);
                                        }
                               }    
                        });
                    });
                   
				}
				}
		}
		
		
		function hapus_rinci(){
		  
                var cgiat = document.getElementById('kdgiat').value;
        		var cskpd = document.getElementById('sskpd').value;
                var crek  = document.getElementById('reke').value;
                var norka = cskpd+'.'+cgiat+'.'+crek;
                var nopo  = document.getElementById('nopo').value;
                var urai  = document.getElementById('uraian').value;
                
                var total_awal = angka(document.getElementById('rektotal_rinci').value) ;
                
                var rows        = $('#dg1').edatagrid('getSelected');
                    urai        = rows.uraian ;
                var total_rinci = angka(rows.total) ;

                var cfm   = confirm("Hapus Uraian "+urai+" ?") ;
                
                if ( cfm == true ){

                    var idx   = $('#dg1').edatagrid('getRowIndex',rows);
                    $('#dg1').datagrid('deleteRow',idx);     
                    $('#dg1').datagrid('unselectAll');
                    
                    var total_rincian = total_awal - total_rinci ;
                    $("#rektotal_rinci").attr("value",number_format(total_rincian,"2",'.',','));
                    
                    /*
                    $(document).ready(function(){
                        $.ajax({
                               type     : 'POST',
                               dataType : 'json',
                               data     : ({vnorka:norka,vnopo:nopo}),
                               url      : '<?php echo base_url(); ?>index.php/rka/thapus_rinci_ar',
                               success  : function(data){
                                        st12=data;
                                        if ( st12=='0'){
                                            alert('Gagal Hapus...!!!');
                                        } else {
                                            section(crek);
                                            alert("Data Telah Terhapus...!!!");
                                        }
                               }    
                        });
                    });
                    */
                }
		}
		
		
		
	
		
    
        
            
        $(function(){
       	    var mskpd = document.getElementById('sskpd').value;
            var mgiat = document.getElementById('kdgiat').value;
			$('#dg1').edatagrid({
				 rowStyler:function(index,row){
					if (row.header==1){
					//return {class:'r1', style:{'color:#fff'}};
						//return 'background-color:#6293BB;color:#fff;';
					   return 'color:red;font-weight:bold;';
						//font-weight:bold;
					}
				 },
				 url           : '<?php echo base_url(); ?>/index.php/akuntansi/kapit_rinci',
                 idField      : 'id',
                 toolbar      : "#toolbar1",              
                 rownumbers   : "true", 
                 fitColumns   : false,
                 singleSelect : "true",
				 loadMsg	  : "Loading....",
				 onAfterEdit  : function(rowIndex, rowData, changes){								
							    },
				 onSelect:function(rowIndex, rowData, changes){							
							  detIndex=rowIndex;
                             // po=rowData.no_po;
                             // $("#noid").attr("value",detIndex);
                              //$("#nopo").attr("value",po);	
                              
						  },
				columns:[[
					{field:'id',
					 title:'id',
					 width:5,
                     hidden:true
					},
					{field:'no_lamp',
					 title:'No Lamp Neraca',
					 width:30
					},
                    {field:'kd_rek5',
					 title:'Kd. Rekening',
					 width:20
					},
					{field:'nm_rek6',
					 title:'Nama Rek. Rinci',
					 width:50
					},
					{field:'harga_satuan',
					 title:'Hrg Sat',
					 width:50,
                     align:'right',
					},
					{field:'tahun_n',
					 title:'Nilai',
					 width:50,
                     align:'right',
					},
					{field:'nilai',
					 title:'Kapitalisasi',
					 width:50,
                     align:'right',
					 styler: function(value,row,index){
						return 'background-color:#bfff94;font-weight:bold;';
					 },
                     },
					{field:'tot_sat_kap',
					 title:'Sat+Kap.',
					 width:50,
                     align:'right',
					 styler: function(value,row,index){
						return 'background-color:#d9e3fc;color:red;font-weight:bold;';
					 },
                     },
                    {field:'tot_kap',
					 title:'Nil+Kap.',
					 width:30,
                     align:'right',
					 styler: function(value,row,index){
						return 'background-color:#d9e3fc;color:red;font-weight:bold;';
					 },
                     }
				]]	
			});
		});
        

        $(document).ready(function() {
            $("#accordion").accordion();
        });
  
        
        function section(kdrek){
            
		    var mskpd = document.getElementById('sskpd').value;
            var mgiat = $("#kdgiat").combogrid("getValue");
			var a     = kdrek ;
            
            if ( mgiat=='' ){
                alert("Pilih Kegiatan Terlebih Dahulu...!!!");
            }
            
            if ( a=='' ){
                alert("Pilih Rekening Terlebih Dahulu...!!!");
            }
            

            $(document).ready(function(){
 			    $("#reke").attr("value",a);            
				$('#section2').click();
                $(function(){
        			$('#dg1').edatagrid({
       				     url          : '<?php echo base_url(); ?>/index.php/akuntansi/kapit_rinci/'+mskpd+'/'+mgiat+'/'+kdrek,
                         idField      : 'id',
                         toolbar      : "#toolbar1",              
                         rownumbers   : "true", 
                         fitColumns   : true,
						 title        : a,
                         singleSelect : "true",
						 onSelect     : function(rowIndex,rowData){
										no_lamp = rowData.no_lamp;
										kd_rek3 = rowData.kd_rek3;
										nm_rek3 = rowData.nmrek3;
										kd_rek5 = rowData.kd_rek5;    
										nm_rek5 = rowData.nm_rek5;
										kd_rek6 = rowData.kd_rek6;
										nm_rek6 = rowData.nm_rek6;
										tahun = rowData.tahun;
										bulan = rowData.bulan;
										merk = rowData.merk;
										no_polisi = rowData.no_polisi;
										fungsi = rowData.fungsi;
										hukum = rowData.hukum;
										lokasi = rowData.lokasi;
										alamat = rowData.alamat;
										sert = rowData.sert;
										luas = rowData.luas;
										jumlah = rowData.jumlah;
										satuan = rowData.satuan;
										harga_satuan = rowData.harga_satuan;
										piutang_awal = rowData.piutang_awal;
										piutang_koreksi = rowData.piutang_koreksi;
										piutang_sudah = rowData.piutang_sudah;
										investasi_awal = rowData.investasi_awal;
										sal_awal = rowData.sal_awal;
										kurang = rowData.kurang;
										tambah = rowData.tambah;
										tahun_n = rowData.tahun_n;
										kondisi_b = rowData.kondisi_b;     
										kondisi_rr = rowData.kondisi_rr;     
										kondisi_rb = rowData.kondisi_rb;     
										keterangan = rowData.keterangan;
										rincian_beban = rowData.rincian_beban;
										no_polis = rowData.no_polis;
										kepemilikan = rowData.kepemilikan;
										xnilkapit = rowData.nilai;
										xnil_kap = rowData.tot_kap;
										xsat_kap = rowData.tot_sat_kap;
										  get(no_lamp,kd_rek3,nm_rek3,kd_rek5,nm_rek5,kd_rek6,nm_rek6,tahun,bulan,merk,no_polisi,fungsi,hukum,lokasi,alamat,sert,luas,satuan,harga_satuan,piutang_awal,piutang_koreksi,piutang_sudah,investasi_awal,sal_awal,kurang,tambah,tahun_n,kondisi_b,kondisi_rr,kondisi_rb,keterangan,jumlah,no_polis,rincian_beban,kepemilikan,xnilkapit,xnil_kap,xsat_kap);
										  validate_input(kd_rek3) ;
										  status_input = 'edit';
							 
                       },
                       onDblClickRow  : function(rowIndex,rowData){
                                        section5();
                                        //document.getElementById('vol1_edit').focus() ;
                       },
                       /*onAfterEdit  : function(rowIndex, rowData, changes){								
										urai = rowData.uraian;
										idx  = rowData.no_po;
										
										vol1=rowData.volume1;
										sat1=rowData.satuan1;
										har1=rowData.harga1;

										vol2=rowData.volume2;
										sat2=rowData.satuan2;

										vol3=rowData.volume3;
										sat3=rowData.satuan3;

										simpan_rincian(idx,urai,vol1,sat1,har1,vol2,sat2,vol3,sat3,kdrek);
								     },*/
						onLoadSuccess:function(data){
										load_sum_rek_rinci(kdrek);	
										//load_sum_rek_rka(kdrek);
										
									 }
        			});
         		});
                    
            });
        }
	 function get(no_lamp,kd_rek3,nm_rek3,kd_rek5,nm_rek5,kd_rek6,nm_rek6,tahun,bulan,merk,no_polisi,fungsi,hukum,lokasi,alamat,sert,luas,satuan,harga_satuan,piutang_awal,piutang_koreksi,piutang_sudah,investasi_awal,sal_awal,kurang,tambah,tahun_n,kondisi_b,kondisi_rr,kondisi_rb,keterangan,jumlah,no_polis,rincian_beban,kepemilikan,xnilkapit,xnil_kap,xsat_kap){
		$("#nomor").attr("value",no_lamp);
		$("#no_simpan").attr("value",no_lamp);
        $("#rek3").combogrid("setValue",kd_rek3);
        $("#nm_rek3").attr("Value",nm_rek3);
		$("#rek5").combogrid("setValue",kd_rek5);
        $("#nm_rek5").attr("Value",nm_rek5);
		$("#rek6").combogrid("setValue",kd_rek6);
        $("#nm_rek6").attr("Value",nm_rek6);
		$("#tahun").combobox("setValue",tahun);
		$("#bulan").combobox("setValue",bulan);
        $("#merk").attr("Value",merk);
        $("#no_polisi").attr("Value",no_polisi);
        $("#fungsi").attr("Value",fungsi);
        $("#hukum").attr("Value",hukum);
		$("#lokasi").combobox("setValue",lokasi);
        $("#alamat").attr("Value",alamat);
        $("#sert").attr("Value",sert);
        $("#luas").attr("Value",luas);
        $("#satuan").attr("Value",satuan);
        $("#harga_satuan").attr("Value",harga_satuan);
        $("#piutang_awal").attr("Value",piutang_awal);
        $("#piutang_koreksi").attr("setValue",piutang_koreksi);
        $("#piutang_sudah").attr("Value",piutang_sudah);
        $("#sal_awal").attr("Value",sal_awal);
        $("#investasi_awal").attr("Value",investasi_awal);
        $("#kurang").attr("Value",kurang);
        $("#tambah").attr("Value",tambah);
        $("#tahun_n").attr("Value",tahun_n);
        $("#kondisi_b").attr("Value",kondisi_b);
        $("#kondisi_rr").attr("Value",kondisi_rr);
        $("#kondisi_rb").attr("Value",kondisi_rb);
        $("#keterangan").attr("Value",keterangan);
        $("#jumlah").attr("Value",jumlah);
        $("#milik").attr("Value",kepemilikan);
        $("#rincian_bebas").attr("Value",rincian_beban);
        $("#no_polis").attr("Value",no_polis);
        $("#harga_awal").attr("Value",'');
        $("#kapit_rincian").attr("Value",xnilkapit);
        $("#kapit_rincian1").attr("Value",xnilkapit);
        $("#sat_kap").attr("Value",xsat_kap);
        $("#nil_kap").attr("Value",xnil_kap);
		
		 var ztot_trans = angka(document.getElementById('tot_trans').value);
		 var ztot_rinci = angka(document.getElementById('tot_rinci').value);
		 var ztahun_n= angka(tahun_n);
		 var z_sisa=(ztot_trans-ztot_rinci)+ztahun_n;
		 $("#trans_tot").attr("Value",number_format(z_sisa,2,'.',','));
		
        }
		
	   function section1(){
		 validate_combo();
         $(document).ready(function(){    
             $('#section1').click();                                               
         });
       }
	   
	   function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });
       }
	   
	   function section3(){
		// validate_combo();
         $(document).ready(function(){    
             $('#section3').click();                                               
         });
       }

	function section5(){
		// validate_combo();
         $(document).ready(function(){    
             $('#section5').click();                                               
         });
       }
	   
	   function tambah_rincian(){
		 cek_rek_aset();
		 $('#nomor').attr('readonly',false);
		 get_nourut();
		 kosong_rincian();
		 sisa_lamp_neraca();
         $(document).ready(function(){    
             $('#section5').click();                                               
         });
       }
	  
	  function sisa_lamp_neraca(){
		 var ztot_trans = angka(document.getElementById('tot_trans').value);
		 var ztot_rinci = angka(document.getElementById('tot_rinci').value);
		 var z_sisa=ztot_trans-ztot_rinci;
		 $("#trans_tot").attr("Value",number_format(z_sisa,2,'.',','));
	  }
	  
	  function cek_rek_aset(){
            var xxrek5 = $("#kdrek5").combogrid('getValue');
            $.ajax({
               url       : '<?php echo base_url(); ?>/index.php/akuntansi/cek_rek_aset',
               type      : 'POST',
               dataType  : 'json',
               data      : ({kdrek5:xxrek5}),
               success   : function(data) {
                    $.each(data, function(i,n){
						$("#rek3").combogrid("setValue",n['kdrek3_u']);
                        //$("#rek3").attr("value",n['kdrek3_u']);
						$("#nm_rek3").attr("value",n['nm_rek3']);
						$("#rek5").combogrid("setValue",n['map_lo']);
						$("#nm_rek5").attr("Value",n['nm_rek5']);   
						validate_input(n['kdrek3_u']);						
						});
               }
            });
        }
		
	 function get_nourut(){
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/no_urut_lamp_neraca',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			$("#nomor").attr("value",data.no_urut);
        		}                                     
        	});  
        }
		
    function load_sum_rek(){                
		var a = document.getElementById('sskpd').value;
        var b = document.getElementById('kdgiat').value;
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b}),
            url:"<?php echo base_url(); ?>index.php/rka/load_sum_rek",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#rektotal").attr("value",n['rektotal']);
                });
            }
         });
        });
    }

    //menghitung total kapitalisasi
    function load_sum_rek_kapit(){                
		var a = document.getElementById('sskpd').value;
        var b = $("#kdgiat").combogrid('getValue');
        var c = $("#kdrek5").combogrid('getValue');
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b,rek:c}),
            url:"<?php echo base_url(); ?>index.php/akuntansi/load_sum_kapit_rinci",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#tot_kapit").attr("value",n['rektotal_kapit']);
                });
            }
         });
        });
    }
	
	//menghitung total transaksi rincian rekening
	function load_sum_rek_rinci(c){                
		var a = document.getElementById('sskpd').value;
        var b = $("#kdgiat").combogrid('getValue');
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({skpd:a,keg:b,rek:c}),
            url:"<?php echo base_url(); ?>index.php/akuntansi/load_sum_kapit_rinci",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#tot_rinci").attr("value",n['rektotal_rinci']);
                });
            }
         });
        });
    }
    
    
    function input(){
        $("#kdrek5").combogrid("setValue","");
        var cek_giat = $("#kdgiat").combogrid('getValue');
        
        if ( cek_giat=='' ){
            alert('Pilih Kegiatan Terlebih Dahulu...!!!');
            exit();
        }
        
		//$("#kdrek5").combogrid("setValue",'');
        $("#kdrek5").combogrid("enable");
        $("#kdrek5").combogrid("setValue",'');
        $("#jenis").combobox("setValue",'');
       
        
        document.getElementById('nmrek5').value      = '';
        document.getElementById('kapitalisasi').value      = '';
        document.getElementById('anggaran').value      = '';
        validate_rekening();
    }
    

    function tambah(){
        var skpd   = document.getElementById('sskpd').value;
        var kegi   = $("#kdgiat").combogrid("getValue");
        var reke   = $("#kdrek5").combogrid("getValue");
        var nmrek5 = document.getElementById('nmrek5').value;
        var anggaran = angka(document.getElementById('anggaran').value);
        var kapit = angka(document.getElementById('kapitalisasi').value);
        var trans = angka(document.getElementById('transaksi').value);
        var jenis = $("#jenis").combobox("getValue");
        if ( kegi == '' ){
            alert('Pilih Kode Kegiatan Terlebih Dahulu...!!!');
            exit();
        }
        if ( reke == '' ){
            alert('Pilih Rekening Terlebih Dahulu...!!!');
            exit();
        }
		        

        $("#dg").datagrid("selectAll");
        var rows = $("#dg").datagrid("getSelections");
        var jrow = rows.length - 1;
        jidx     = jrow + 1 ;

        $("#dg").edatagrid('appendRow',{kd_kegiatan:kegi,kd_rek5:reke,nm_rek5:nmrek5,nil_ang:anggaran,jenis:jenis,kapitalisasi:kapit,nil_trans:trans});
        //$('#dg').datagrid('appendRow',{kd_kegiatan:xkdkegi,nm_kegiatan:xnmkegi,jns_kegiatan:xjns,lanjut:xljt});

        $(document).ready(function(){
        $.ajax({
           type     : "POST",
           dataType : "json",
           data     : ({kd_skpd:skpd,kd_kegiatan:kegi,kd_rek5:reke,nil_ang:anggaran,kapitalisasi:kapit,jenis:jenis,nil_trans:trans}),
           url      : '<?php echo base_url(); ?>index.php/akuntansi/tsimpan_kapitalisasi', 
           success  : function(data){
                      st12 = data;
                      if ( st12 == '1' ){
                        alert("Data Tersimpan...!!!");
                      } else {
                        alert("Gagal Simpan...!!!");
                      }
                      }
        });
        });
		
        $("#dg").datagrid("unselectAll");
        validate_combo();
        $('#dg').datagrid('reload');
       /* $("#kdrek5").combogrid("disable");
        $("#kdrek5").combogrid("setValue",'');
        $("#nilairek").attr("value",0);
        document.getElementById('nmrek5').value = '';
        $("#sdana1").combogrid("setValue",'');
        $("#sdana2").combogrid("setValue",'');
        $("#sdana3").combogrid("setValue",'');
        $("#sdana4").combogrid("setValue",'');
		*/
    }
    
    
    function btl(){
        //$("#kdrek5").combogrid("setValue",'');
        //$("#kdrek5").combogrid("setValue",'');
        $("#sdana1").combogrid("setValue",'');
        $("#sdana2").combogrid("setValue",'');
        $("#sdana3").combogrid("setValue",'');
        $("#sdana4").combogrid("setValue",'');
		//$("#kdrek5").combogrid("disable");
        document.getElementById('nilairek').value = 0;
        document.getElementById('nmrek5').value   = '';
        $("#dg").datagrid("unselectAll");
        
    }
    

    function keluar(){
        $("#dialog-modal").dialog('close');
        $('#dg_rek').datagrid('unselectAll');
        $('#dg').edatagrid('reload');
    } 
      
		function insert(){
			$('#dg1').datagrid('insertRow',{
				index:detIndex,
				row:{uraian:''				
					}
			});
			$('#dg1').datagrid('beginEdit',detIndex+1);		
		}	

        
        //DASAR HUKUM ==================================================================================================
       
    
    //load dasar hukum pada combo giat==========================================================
	var sell = new Array();
	var max  = 0;
	function getcek(){
		var ids = [];  
		var a=null;
		var rows = $('#dg2').edatagrid('getSelections');  
		for(var i=0; i<rows.length; i++){  
		    a=rows[i].ck;
			max=i;
			if (a!=null){
				sell[i]=a-1;
			}else{
				sell[i]=1000;			
			}
		}  
	}
	
	function setcek(){
		for(var i=0; i<max+1; i++){ 
			if (sell[i]!=1000){
				selectRecord(sell[i]);
			}
		} 		
	}


	function selectall(){
		max  = 0;
		$('#dg2').edatagrid('selectAll');
		getcek();
		Unselectall();
		setcek();
	}

	function Unselectall(){
		$('#dg2').edatagrid('unselectAll');
	}


	function selectRecord(rec){
		$('#dg2').edatagrid('selectRecord',rec);
	}
	
	
	function edit_nil_kapit(){
		var kd_kegi 	= $("#kdgiat").combogrid("getValue") ; 
		var kd_rek5 	= $("#kdrek5").combogrid("getValue") ; 
        var a_hide  	= document.getElementById('no_simpan').value;
        var b       	= $("#rek3").combogrid("getValue") ;     		
		var xnilaikapit 	= angka(document.getElementById('kapit_rincian').value);
		var xnilaikapit1 	= angka(document.getElementById('kapit_rincian1').value);
		var xtot_kapit 		= angka(document.getElementById('tot_kapit').value);
		var xtot_kapit_rek 	= angka(document.getElementById('tot_kapit_rek').value);
		var sel1			= xnilaikapit-xnilaikapit1; 1
		var sel2			= xtot_kapit_rek-xtot_kapit; 0
		/*
		if(sel1>sel2){
			alert("melebihi total Kapitalisasi");
			exit();
		}
		*/
		$(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/akuntansi/update_rincian_kapitalisasi',
                    data     : ({tabel:'trdkapitalisasi',kdgiat:kd_kegi,kdrek5:kd_rek5,no_lamp:a_hide,xnil:xnilaikapit}),
                    dataType : "json",
					 beforeSend:function(xhr){
						$("#loading").dialog('open');
						},
                    success  : function(data){
                        status = data;
                        if (status=='0'){
							$("#loading").dialog('close');
                            alert('Gagal Simpan..!!');
                            exit();
                        } else if(status=='1'){
								  $("#loading").dialog('close');
                                  alert('Nilai Melebihi Total Kapitalisasi..!!');
                                  exit();
                               } else {
								  $("#loading").dialog('close');
                                  status_input = 'edit';
								  load_sum_rek_kapit();
								  alert('Data Tersimpan..!!');
								  section(kd_rek5);
                               }
                    }
                });
            }); 
		
	}

	 
    
   
     
     function kembali_std(){
        $("#section2").click();
        $("#dialog-modal").dialog("close");
     }

     var sell_std = new Array();
	 var max_std  = 0;
	  
     function getcek_std(){
    	var ids   = [];  
		var a     = null;
		var rows  = $('#dg_std').edatagrid('getSelections');  
		for(var i=0; i<rows.length; i++){  
		    a       = rows[i].ck;
			max_std = i;
			if (a!=null){
				sell_std[i]=a-1;
			}else{
				sell_std[i]=1000;			
			}
		}  
	  }
	
	 
     function setcek_std(){
		for(var i=0; i<max+1; i++){ 
			if (sell_std[i]!=1000){
				selectRecord_std(sell_std[i]);
			}
		} 		
	 }


	 function selectall_std(){
		max_std = 0;
		$('#dg_std').edatagrid('selectAll');
		getcek_std();
		Unselectall_std();
		setcek_std();
	 }

	 
     function Unselectall_std(){
		$('#dg_std').edatagrid('unselectAll');
	 }
     
     function selectRecord_std(rec){
		$('#dg_std').edatagrid('selectRecord',rec);
	 }
     
    
    
    
    
   
	
	
function hitung_saldo_awal(){
        var jumlah = document.getElementById('jumlah').value;
		if(jumlah==''){
			jumlah=0;
		} else {
			jumlah=jumlah;
		}
		var jum=angka(jumlah);
        var harga_satuan = document.getElementById('harga_satuan').value;
		if(harga_satuan==''){
			harga_satuan=0;
		} else {
			harga_satuan=harga_satuan;
		}
		var hrg_satuan=angka(harga_satuan);
        saldo_awal=jum*hrg_satuan;
		$("#harga_awal").attr("Value",number_format(saldo_awal,2,'.',','))		
     }

function hitung_harga_satuan(){
        var transaksi = document.getElementById('trans_tot').value;
		var trans=angka(transaksi);
        var jumlah = document.getElementById('jumlah').value;
		var hrg_satuan=trans/jumlah;
		$("#harga_satuan").attr("Value",number_format(hrg_satuan,0,'.',','))
		var sis_hrg = trans%jumlah;
		if((sis_hrg<=5)&&(sis_hrg>0)){
			alert("Sisa kurang dari 5 Rupiah!");
		}
		hitung_saldo_awal();
     }
	 
	 
	 function kosong_rincian(){
        status_input = "tambah";
        $("#nomor").attr("value",'');
		$("#no_simpan").attr("value",'');
        $("#rek3").combogrid("setValue",'');
        $("#nm_rek3").attr("Value",'');
		$("#rek5").combogrid("setValue",'');
        $("#nm_rek5").attr("Value",'');
		$("#rek6").combogrid("setValue",'');
        $("#nm_rek6").attr("Value",'');
		$("#bulan").combobox("setValue",'');
        $("#merk").attr("Value",'');
        $("#no_polisi").attr("Value",'');
        $("#fungsi").attr("Value",'');
        $("#hukum").attr("Value",'');
        $("#lokasi").attr("Value",'');
        $("#alamat").attr("Value",'');
        $("#sert").attr("Value",'');
        $("#luas").attr("Value",'');
        $("#harga_awal").attr("Value",'');
        $("#satuan").attr("Value",'');
        $("#harga_satuan").attr("Value",'');
        $("#piutang_awal").attr("Value",'');
        $("#piutang_koreksi").attr("setValue",'');
        $("#piutang_sudah").attr("Value",'');
        $("#sal_awal").attr("Value",'');
        $("#investasi_awal").attr("Value",'');
        $("#kurang").attr("Value",'');
        $("#tambah").attr("Value",'');
        $("#tahun_n").attr("Value",'');
        $("#kondisi_b").attr("Value",'');
        $("#kondisi_rr").attr("Value",'');
        $("#kondisi_rb").attr("Value",'');
        $("#keterangan").attr("Value",'');
		$("#milik").attr("Value",'');
        $("#rincian_bebas").attr("Value",'');
        $("#no_polis").attr("Value",'');
        $("#jumlah").attr("Value",'');
        }
	
	
	 function hsimpan(){ 
		var kd_kegi = $("#kdgiat").combogrid("getValue") ; 
		var kd_rek5 = $("#kdrek5").combogrid("getValue") ; 
        var a       = document.getElementById('nomor').value;
        var a_hide  = document.getElementById('no_simpan').value;
        var b       = $("#rek3").combogrid("getValue") ;     
        var c       = document.getElementById('nm_rek3').value; 
        var d       = $("#rek5").combogrid("getValue") ; 
        var e       = document.getElementById('nm_rek5').value;
        var f       = $("#rek6").combogrid("getValue") ; 
        var g       = document.getElementById('nm_rek6').value;
        var h       = $("#tahun").combobox("getValue") ;
		var h_b     = $("#bulan").combobox("getValue") ;
        var i       = document.getElementById('merk').value;
        var j       = document.getElementById('no_polisi').value;
        var k       = document.getElementById('fungsi').value;
        var l       = document.getElementById('hukum').value;
        var m       = $("#lokasi").combobox("getValue") ;
        var n       = document.getElementById('alamat').value;
		var o       = document.getElementById('sert').value;
        var p       = document.getElementById('luas').value;
        var q       = document.getElementById('satuan').value;
        var r       = document.getElementById('harga_satuan').value;
        var s       = document.getElementById('piutang_awal').value;
        var t       = document.getElementById('piutang_koreksi').value;
		var u       = document.getElementById('piutang_sudah').value;
        var v       = document.getElementById('investasi_awal').value;
        var w       = document.getElementById('sal_awal').value;
        var x       = document.getElementById('kurang').value;
        var y       = document.getElementById('tambah').value;
        var z       = document.getElementById('tahun_n').value;
		var aa       = 0;
        var bb       = document.getElementById('kondisi_b').value;
        var cc       = document.getElementById('kondisi_rr').value;
        var dd       = document.getElementById('kondisi_rb').value;
        var ee       = document.getElementById('keterangan').value;
        var ff       = document.getElementById('dn').value;
        var gg       = document.getElementById('jumlah').value;
        var hh       = document.getElementById('milik').value;
        var ii       = document.getElementById('rincian_bebas').value;
        var jj       = document.getElementById('no_polis').value;
        var kap      = document.getElementById('kapitalisasi').value;
        var xsisa      = document.getElementById('trans_tot').value;

		if(angka(z)>angka(xsisa)){
			alert('Nilai Pengadaan melebihi Sisa Uang Transaksi');
			exit();
		}
		
		if (p==''){
			p=0;
		}else{
			p=angka(p);
		}
		
		if (r==''){
			r=0;
		}else{
			r=angka(r);
		}
        
		if (s==''){
			s=0;
		}else{
			s=angka(s);
		}
		if (t==''){
			t=0;
		}else{
			t=angka(t);
		}
		if (u==''){
			u=0;
		}else{
			u=angka(u);
		}
		if (v==''){
			v=0;
		}else{
			v=angka(v);
		}
		if (w==''){
			w=0;
		}else{
			w=angka(w);
		}
		if (x==''){
			x=0;
		}else{
			x=angka(x);
		}
		
		if (y==''){
			y=0;
		}else{
			y=angka(y);
		}
		
		if (z==''){
			z=0;
		}else{
			z=angka(z);
		}
		if (gg==''){
			gg=0;
		}else{
			gg=angka(gg);
		}
		if (kap==''){
			kap=0;
		}else{
			kap=angka(kap);
		}
		if (bb==''){
			bb=0;
		}else{
			bb=angka(bb);
		}
		if (cc==''){
			cc=0;
		}else{
			cc=angka(cc);
		}
		if (dd==''){
			dd=0;
		}else{
			dd=angka(dd);
		}
		
        if ( a == '' ){
            alert("Isi Nomor Terlebih Dahulu") ;
            exit();
        }
		 if ( h == '' ){
            alert("Isi Tahun Terlebih Dahulu") ;
            exit();
        }
        
		if ( h_b == '' ){
            h_b=0;
        }else{
			h_b=angka(h_b);
		}
		
		if(gg != (bb+cc+dd)){
			alert('Jumlah Barang tidak sesuai dengan Jumlah Kondisi Barang');
			exit();
		}
		
		var norinci  = ff+'.'+kd_kegi+'.'+kd_rek5 ;
		if(status_input == "tambah"){
			$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:"lamp_aset",field:"no_lamp",tabel2:"trdkapitalisasi",field2:"no_lamp"}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0){
						alert("Nomor Bisa dipakai");
		
		//---------
            lcinsert = "(no_rinci,kd_kegiatan,kd_rek5_trans,no_lamp, kd_rek3, nm_rek3, kd_rek5, nm_rek5, kd_rek6, nm_rek6, tahun, bulan, merk, no_polisi, fungsi, hukum, lokasi, alamat, sert, luas, satuan, harga_satuan, piutang_awal, piutang_koreksi, piutang_sudah, investasi_awal, sal_awal, kurang, tambah, tahun_n, akhir, kondisi_b, kondisi_rr, kondisi_rb, keterangan,kd_skpd,jumlah,kepemilikan,rincian_beban,no_polis,nilai,kapitalisasi)"; 
			lcvalues = "('"+norinci+"','"+kd_kegi+"','"+kd_rek5+"','"+a+"', '"+b+"', '"+c+"', '"+d+"', '"+e+"', '"+f+"', '"+g+"','"+h+"','"+h_b+"','"+i+"','"+j+"','"+k+"','"+l+"','"+m+"','"+n+"','"+o+"',"+p+",'"+q+"',"+r+",     "+s+",        "+t+" ,    "+u+",       "+v+",      "+w+", "+x+" ,"+y+", "+z+",'"+aa+"','"+bb+"','"+cc+"',     '"+dd+"', '"+ee+"', '"+ff+"', "+gg+", '"+hh+"', '"+ii+"', '"+jj+"','"+aa+"','"+kap+"')";
			$(document).ready(function(){
                $.ajax({
                    type     : "POST",
                    url      : '<?php echo base_url(); ?>/index.php/tukd/simpan_lamp_aset',
                    data     : ({tabel:'trdkapitalisasi',kolom:lcinsert,nilai:lcvalues,cid:'no_lamp',lcid:a}),
                    dataType : "json",
                    success  : function(data){
                        status = data;
                        if (status=='0'){
                            alert('Gagal Simpan..!!');
                            exit();
                        } else if(status=='1'){
                                  alert('Data Sudah Ada..!!');
                                  exit();
                               } else {
                                  alert('Data Tersimpan..!!');
								  $("#no_simpan").attr("value",a);
								  $('#nomor').attr('readonly',true);
                                  status_input = 'edit';
								  section(kd_rek5);
                                  //exit();
                               }
                    }
                });
            });   
           
		//----------
		
		}
		}
		});
		});
		
        
            
        } else {
			$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:a,tabel:"lamp_aset",field:"no_lamp",tabel2:"trdkapitalisasi",field2:"no_lamp"}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && a!=a_hide){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || a==a_hide){
						alert("Nomor Bisa dipakai");
			
			
		//---------
		lcquery = " UPDATE trdkapitalisasi SET no_rinci='"+norinci+"', kd_kegiatan='"+kd_kegi+"',kd_rek5_trans='"+kd_rek5+"', no_lamp ='"+a+"', kd_rek3='"+b+"', nm_rek3='"+c+"', kd_rek5='"+d+"', nm_rek5='"+e+"', kd_rek6='"+f+"', nm_rek6='"+g+"', tahun='"+h+"', bulan='"+h_b+"', merk='"+i+"', no_polisi='"+j+"', fungsi='"+k+"', hukum='"+l+"', lokasi='"+m+"', alamat='"+n+"', sert='"+o+"', luas='"+p+"', satuan='"+q+"', harga_satuan='"+r+"', piutang_awal='"+s+"', piutang_koreksi='"+t+"', piutang_sudah='"+u+"', investasi_awal='"+v+"', sal_awal='"+w+"', kurang='"+x+"', tambah='"+y+"', tahun_n='"+z+"', kondisi_b='"+bb+"', kondisi_rr='"+cc+"', kondisi_rb='"+dd+"', keterangan='"+ee+"',kd_skpd ='"+ff+"',jumlah ='"+gg+"',kepemilikan ='"+hh+"',rincian_beban ='"+ii+"',no_polis ='"+jj+"',nilai ='"+aa+"',kapitalisasi ='"+kap+"' where no_lamp='"+a_hide+"' AND kd_skpd ='"+ff+"' "; 

//			alert(lcquery);
//exit();
            $(document).ready(function(){
            $.ajax({
                type     : "POST",
                url      : '<?php echo base_url(); ?>/index.php/tukd/update_tukd',
                data     : ({st_query:lcquery,tabel:'trdkapitalisasi',cid:'no_lamp',lcid:a,lcid_h:a_hide}),
                dataType : "json",
                success  : function(data){
                           status=data ;
                                                		
                        if ( status=='1' ){
							//alert("aaaa");
                            alert('Nomor  Sudah Terpakai...!!!,  Ganti Nomor ...!!!');
                            exit();
                        }
                        
                        if ( status=='2' ){
							alert('Data Tersimpan...!!!');
                            status_input = 'edit';
							$("#no_simpan").attr("value",a);
                            section(kd_rek5);
                        }
                        
                        if ( status=='0' ){
                            alert('Gagal Simpan...!!!');
                            exit();
                        }
                        
                    }
            });
            });
		
		//-----------
				}
			}
		});
     });
		
        }
        
    }
	
</script>


<STYLE TYPE="text/css"> 
input.right{ 
         text-align:right; 
         } 
</STYLE> 


</head>
<body>

<div id="content">

   <p id="p1" style="font-size: x-large;color: red;"></p><br />
   <table style="border-collapse:collapse;border-style:hidden;" width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
   <tr style="border-style:hidden;">
   <td>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="sskpd" name="sskpd" readonly="true" style="width:170px;border: 0;" />
   &nbsp;&nbsp;<input id="nmskpd" name="nmskpd" readonly="true" style="width: 620px; border:0;  " /></td>
   </tr>
   <tr style="border-style:hidden;">
   <td>KEGIATAN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="kdgiat" name="kdgiat" style="width:170px;" />  
   &nbsp;&nbsp;&nbsp;<input id="nmgiat" name="nmgiat" readonly="true" style="width:620px;border:0;background-color:transparent;color: black;" disabled="true"/>
   <input type="hidden" id="jnskegi" name="jnskegi" style="width:20px;" /></td>
   </tr>
   </table>

<div id="accordion">



<h2><a href="#" id="section1" onclick="javascript:validate_combo()">Input Kapitalisasi</a></h2>
   
   <div  style="height:700px;">      
   
       <table border='1'  style="border-spacing:0px;padding:0px 0px 0px 0px;border-collapse:collapse;width:880px;border-style: ridge;" >
       
       <tr style="border-bottom-style:hidden;">
       <td colspan="5" style="border-bottom-style:hidden;"></td>
       </tr>
       
       <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">REKENING</td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:770px;border-bottom-style:hidden;" colspan="4"><input id="kdrek5" name="kdrek5" style="width:170px;" />  
           <input id="nmrek5" name="nmrek5" readonly="true" style="width:570px;border:0;background-color:transparent;color:black;" disabled="true" />
       </td>
       </tr>
	   <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">ANGGARAN</td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:170px;border-bottom-style:hidden;border-right-style:hidden;"><input id="anggaran" name="anggaran" readonly="true" type="decimal" style="width:170px;text-align:right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>  
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:260px;border-bottom-style:hidden;"></td> 
       </tr>
       <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">KAPITALISASI</td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:170px;border-bottom-style:hidden;border-right-style:hidden;"><input id="kapitalisasi" name="kapitalisasi"  type="decimal" style="width:170px;text-align:right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>  
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:260px;border-bottom-style:hidden;"></td> 
       </tr>
       <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">NILAI TRANS</td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:170px;border-bottom-style:hidden;border-right-style:hidden;"><input id="transaksi" name="transaksi"  type="decimal" readonly="true" style="width:170px;text-align:right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/></td>  
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:260px;border-bottom-style:hidden;"></td> 
       </tr>
       <tr style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;border-bottom-style:hidden;">
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:110px;border-bottom-style:hidden;border-right-style:hidden;">JENIS</td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:170px;border-bottom-style:hidden;border-right-style:hidden;">
	   <select id="jenis" class="easyui-combobox" name="jenis" style="width:100px;">
			<option value="">Pilih Jenis</option>
			<option value="Y">Y</option>
			<option value="N">N</option>
			<option value="X">X</option>
		</select></td>
       <td style="border-spacing:3px;padding:3px 3px 3px 3px;border-collapse:collapse;width:600px;border-bottom-style:hidden;" colspan="3"></td>
       </tr>
       <tr style="border-bottom-style:hidden;">
       <td colspan="5" align="center" style="border-bottom-style:hidden;">
	  <button id="add1" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:buat_baru();">Buat Baru!</button>
       <button id="input" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:input()">Tambah</button>
       <button id="btl" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:btl()">Batal</button>
       <button id="delrek" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus()">Hapus</button>
       <button id="add" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:tambah()">Simpan</button>
       </td>
       </tr>
       <tr style="border-bottom-color:black;height:1px;" >
       <td colspan="5" style="border-bottom-color:black;height:1px;"></td>
       </tr>
       </table>
       
       <!--<table border='0' width="100%" >
       <tr>
       <td align='right'>
       <button id="add" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</button>
       <button id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</button>
       <button id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">Simpan</button>
       <button id="cancel" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Batal</button>
       </td>
       </tr>
       </table>-->
       
       <table id="dg" title="Rekening Kapitalisasi" style="width:880px;height:400px;" >          
       </table>  
        <div id="toolbarx">
    		&nbsp;&nbsp; <button  class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:refresh()">Refresh Tabel</button>
            <button  class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:hitung_kapitalisasi()">Hitung Kapitalisasi</button>
            <table style="width:880px;height:10px;border-style:hidden;">
            <!--<tr><td align="right">
			
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <B>Total</B>&nbsp;&nbsp;<input class="right" type="text" name="rektotal" id="rektotal"  style="width:200px;text-align:right;"  readonly="true"/>
            </td></tr-->
            </table>
        </div>
    </div>
    
<h3><a href="#" id="section2">Rincian Kapitalisasi</a></h3>
    
    <div>
		<td align='left' style="border-style: hidden;">
        <B>Nilai Kapitalisasi</B>&nbsp;&nbsp;<input class="right" type="text" name="kapit_rincian" id="kapit_rincian"  style="width:150px" align="right" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/>
		&nbsp;&nbsp;<input class="right" type="hidden" name="kapit_rincian1" id="kapit_rincian1"  style="width:150px" align="right" onkeypress="javascript:return(currencyFormat(this,',','.',event))"/>
		&nbsp;&nbsp;<button  class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:edit_nil_kapit();">Edit Nilai</button>
		<br>
		</td>        
		<table id="dg1"  style="width:875px;height:370px;"> 
        </table>  
        <table border='1' style="width:870px;">
        <tr style="border-style: hidden;">
        <td style="border-style: hidden;">
			<button  class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:refresh_rinci()">Refresh Tabel</button>
            <button id="add1" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah_rincian();">Tambah</button>
            <button id="add1" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="javascript:section5();">Edit</button>
    		<button id="del1" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_rincian();">Hapus</button>
        </td>
        <td align='right' style="border-style: hidden;">
        <B>Total</B>&nbsp;&nbsp;&nbsp;&nbsp;<input class="right" type="text" name="tot_rinci" id="tot_rinci"  style="width:200px" align="right" readonly="true" />
        </td>
        
        </tr>
        
        <tr>
        <td style="border-style: hidden;">
		<button id="del1" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:hitung_rincian_kapit();">Hitung Kapitalisasi</button>
		<button id="hit" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:transfer_lamp();">Transfer Lamp. Neraca</button>
        <button class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="javascript:section1()">Kembali</button>
        </td>
		<td align='right' style="border-style: hidden;">
        <B>Total Transaksi </B>&nbsp;&nbsp;&nbsp;&nbsp;<input class="right" type="text" name="tot_trans" id="tot_trans"  style="width:200px" align="right" readonly="true" />
        </td>
        </tr>
		<td colspan="2" align='right' style="border-style: hidden;">
        <B>Total Kapitalisasi </B>&nbsp;&nbsp;&nbsp;&nbsp;<input class="right" type="text" name="tot_kapit" id="tot_kapit"  style="width:200px" align="right" readonly="true" />
        </td>
		</tr>
		<tr>
		<td colspan="2" align='right' style="border-style: hidden;">
        <B>Total Kapitalisasi Rekening </B>&nbsp;&nbsp;&nbsp;&nbsp;<input class="right" type="text" name="tot_kapit_rek" id="tot_kapit_rek"  style="width:200px" align="right" readonly="true" />
        </td>
		</tr>
       
        
        </table>
    </div>   

<h3><a href="#" id="section5" >Lampiran Neraca</a></h3>

    <div  style="height:350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>

   <fieldset style="width:850px;height:950px;border-color:white;border-style:hidden;border-spacing:0;padding:0;">            
   <table border='1' style="font-size:11px"  >
  <tr>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true;"/></td>
				<td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;">&nbsp;&nbsp;</td>
				<td style="border-bottom: double 1px red;border-top: double 1px red;" colspan = "2"><i>Tidak Perlu diisi atau di Edit</i></td>
                    
            </tr> 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
   <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
 </tr>  

 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td width='8%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">SKPD</td>
   <td colspan ="3" width="53%"  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" >     
      &nbsp;<input id="dn" name="dn"  readonly="true" style="width:130px; border: 0;"/> &nbsp;&nbsp;&nbsp; <input id="nama_skpd" name="nama_skpd"  readonly="true" style="width:300px; border: 0; " /> </td> 
 </tr>

 
<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Nomor Lamp.</td>
     <td colspan="2" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" > <input id="nomor" name="nomor" style="width: 150px;" > 
     <td colspan="1" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > <b>TOTAL TRANS. </b><input id="trans_tot" name="trans_tot" style="width: 150px; border:0; text-align: right;" > 

 </tr> 

  <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rek. Kelompok</td>
     <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > <input id="rek3" name="rek3" style="width: 190px;" > 
   &nbsp;&nbsp;&nbsp; <input id="nm_rek3" name="nm_rek3"  readonly="true" style="width:300px; border: 0;"/> </td> 
 </tr> 
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening</td>
     <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > <input id="rek5" name="rek5" style="width: 190px;" > 
   &nbsp;&nbsp;&nbsp; <input id="nm_rek5" name="nm_rek5"  readonly="true" style="width:300px; border: 0;"/> </td> 
 </tr>
 
 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
   <td style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">Rekening Rinci</td>
     <td colspan="3" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;" > <input id="rek6" name="rek6" style="width: 190px;" > 
   &nbsp;&nbsp;&nbsp; <input id="nm_rek6" name="nm_rek6"  readonly="true" style="width:300px; border: 0;"/> </td> 
 </tr>
 

 <tr style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;">
 
                <td style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;" colspan="5">
                    <div>
                        <table>
                            <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "tahun_oleh1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Tahun Perolehan</td>
                                <td id = "tahun_oleh0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"> <?php $thang =  date("Y");
																																								$thang_maks = $thang + 3 ;
																																								$thang_min = $thang - 15 ;
																																								echo '<select id="tahun" class="easyui-combobox" name="tahun" style="width:140px;">';
																																								echo "<option value=".$thang.">".$thang."</option>";
																																								for ($th=$thang_min ; $th<=$thang_maks ; $th++)
																																								{
																																									echo "<option value=$th>$th</option>";
																																								}
																																								echo '</select>';?>																																				
																																								</td>
								<td id = "bulan_oleh1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Bulan Perolehan</td>
                                <td id = "bulan_oleh0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><select name="bulan" id="bulan" class="easyui-combobox" style=" width:140px;">
																																							 <option value=""> Pilih Bulan </option>     
																																							 <option value="1"> Januari</option>
																																							 <option value="2"> Februari</option>
																																							 <option value="3"> Maret</option>
																																							 <option value="4"> April</option>
																																							 <option value="5"> Mei</option>
																																							 <option value="6"> Juni</option>
																																							 <option value="7"> Juli</option>
																																							 <option value="8"> Agustus</option>
																																							 <option value="9"> September</option>
																																							 <option value="10"> Oktober</option>
																																							 <option value="11"> November</option>
																																							 <option value="12"> Desember</option>
																																						   </td>
                                <td id = "merk1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Merk/Type</td>
                                <td id = "merk0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="merk" style="width: 140px;" /></td>
							</tr>
							 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "no_polis1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Nama & No. Polis Asuransi</td>
                                <td id = "no_polis0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="no_polis"/></td>
                               </tr>
							 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "no_polisi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">No. Polisi</td>
                                <td id = "no_polisi0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="no_polisi"/></td>
                                <td id = "fungsi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Fungsi</td>
                                <td id = "fungsi0"style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="fungsi" style="width: 140px;" /></td>
							</tr>
							 <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "hukum1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Dasar Hukum</td>
                                <td id = "hukum0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="hukum"/></td>
                                <td id = "lokasi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Lokasi</td>
                                <td id = "lokasi0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><select name="lokasi" id="lokasi" class="easyui-combobox" style=" width:200px;">
																																							 <option value=""> Pilih Lokasi </option>     
																																							 <option value="Kota Pontianak"> Kota Pontianak</option>
																																							 <option value="Kota Singkawang"> Kota Singkawang</option>
																																							 <option value="Kabupaten Mempawah"> Kabupaten Mempawah</option>
																																							 <option value="Kabupaten Sanggau"> Kabupaten Sanggau</option>
																																							 <option value="Kabupaten Sintang"> Kabupaten Sintang</option>
																																							 <option value="Kabupaten Kapuas Hulu"> Kabupaten Kapuas Hulu</option>
																																							 <option value="Kabupaten Ketapang"> Kabupaten Ketapang</option>
																																							 <option value="Kabupaten Landak"> Kabupaten Landak</option>
																																							 <option value="Kabupaten Bengkayang"> Kabupaten Bengkayang</option>
																																							 <option value="Kabupaten Sambas"> Kabupaten Sambas</option>
																																							 <option value="Kabupaten Sekadau"> Kabupaten Sekadau</option>
																																							 <option value="Kabupaten Melawi"> Kabupaten Melawi</option>
																																							 <option value="Kabupaten Kayong Utara"> Kabupaten Kayong Utara</option>
																																							 <option value="Kabupaten Kubu Raya"> Kabupaten Kubu Raya</option>
																																							 <option value="Lain-lain"> Lain-lain</option>
																																						   </td>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "alamat1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">ALamat</td>
                                <td id = "alamat0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="alamat" style="width: 140px;" /></td>   
								<td id = "sert1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">No. Sertifikat</td>
                                <td id = "sert0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="sert" style="width: 140px;" /></td>   
							</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "jumlah1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Jumlah</td>
                                <td id = "jumlah0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="jumlah"  style="width: 140px;text-align: right;" onkeyup="javascript:hitung_harga_satuan();"  " /></td>   
								<td id = "luas1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Luas</td>
                                <td id = "luas0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="luas"  style="width: 140px;text-align: right;" onkeyup="formatangka(this);replaceChars(document.nilai.a.value);" /></td>   
								</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "harga_satuan1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Harga Satuan</td>
                                <td id = "harga_satuan0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="harga_satuan"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_saldo_awal();" /></td>   
								<td id = "satuan1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Satuan</td>
                                <td id = "satuan0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="satuan" style="width: 140px;" /></td>   
							</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
								<td id = "rincian_bebas1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Rincian Beban</td>
                                <td id = "rincian_bebas0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="rincian_bebas" style="width: 140px;" /></td>   
								<td id = "piutang_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Piutang Awal</td>
                                <td id = "piutang_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="piutang_awal"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td> 
							</tr>
							
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "piutang_koreksi1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Piutang Koreksi</td>
                                <td id = "piutang_koreksi0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="piutang_koreksi"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung_piutang_koreksi();"/> </td>  
								<td id = "piutang_sudah1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Piutang Setelah Koreksi</td>
                                <td id = "piutang_sudah0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="piutang_sudah"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td>
								<td id = "milik1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kepemilikan (%)</td>
                                <td id = "milik0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="milik"  style="width: 140px;text-align: right;" /></td>
							</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "harga_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Jumlah x Harga Satuan</td>
                                <td id = "harga_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="harga_awal" style="width: 140px;text-align: right; background-color:yellow;" readonly="true;"  /></td>   
							</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "investasi_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Investasi Awal</td>
                                <td id = "investasi_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="investasi_awal"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
								<td id = "sal_awal1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Saldo Awal</td>
                                <td id = "sal_awal0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="sal_awal" style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"  /></td>   
							</tr>
							
							
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "kurang1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Berkurang</td>
                                <td id = "kurang0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="kurang"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
								<td id = "tambah1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Bertambah</td>
                                <td id = "tambah0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="tambah"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
								</tr>																														
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "tahun_n1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Dana/Pengadaan Tahun </td>
                                <td id = "tahun_n0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="numeric" id="tahun_n"  style="width: 140px;text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
								<td id = "akhir1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Saldo Akhir</td>
                                <td id = "akhir0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="text" id="akhir" style="width: 140px;" onkeypress="return(currencyFormat(this,',','.',event))"  /></td>   
								</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "kondisi_b1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kondisi B </td>
                                <td id = "kondisi_b0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="number" id="kondisi_b" style="width: 140px;" /></td>   
								<td id = "kondisi_rr1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kondisi RR</td>
                                <td id = "kondisi_rr0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="number" id="kondisi_rr" style="width: 140px;" /></td>   
								</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "kondisi_rb1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Kondisi RB</td>
                                <td id = "kondisi_rb0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true"><input type="number" id="kondisi_rb" style="width: 140px;" /></td>   
							</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "keterangan1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" hidden = "true">Keterangan</td>
                                <td colspan ="3" id = "keterangan0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;"><textarea name="keterangan" id="keterangan" cols="30" rows="1" ></textarea></td>   
							</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "sat_kap0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >Satuan+Kap.</td>
                                <td id = "sat_kap1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" ><input type="text" id="sat_kap" style="width: 140px;" /></td>   
							</tr>
							<tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
                                <td id = "nil_kap0" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" >Nilai+Kap.</td>
                                <td id = "nil_kap1" style="border-bottom:double 1px red;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;" ><input type="text" id="nil_kap" style="width: 140px;" /></td>   
							</tr>
                        </table> 
                    </div>
                
                </td>                
   </tr>
 
     <tr style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">
       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='53%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='8%'  style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;border-right-style:hidden;">&nbsp;</td>
       <td width='31%' style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;">&nbsp;</td>
     </tr>  

       <tr style="border-spacing: 3px;padding:3px 3px 3px 3px;">
                <td colspan="4" align='right' style="border-bottom-color:black;border-spacing: 3px;padding:3px 3px 3px 3px;" >
                <a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true"  onclick="javascript:hsimpan();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:section2();">Kembali</a>
       </tr>
</table>

    
       
   </div> 	
	
  
</div>
		<div id="loading" title="Loading...">
			<table align="center">
			<tr align="center"><td><img id="search1" height="50px" width="50px" src="<?php echo base_url();?>/image/loadingBig.gif"  /></td></tr>
			<tr><td>Loading...</td></tr>
			</table>
			</div>

<div id="dialog-modal" title="">

    <p class="validateTips"></p> 
    <fieldset>        
    <table id="dg_std" title="Pilih Standard" style="width:930px;height:300px;">  
    </table>  
    
    <table style="width:930px;height:20px;" border="0">
        <tr>
        <td align="center" colspan='2'>&nbsp;</td>
        </tr>

        <tr>
        <td align="center" >
        <button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:pilih_std();">Pilih</button>
        <button class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="javascript:kembali_std();">Kembali</button></td>
        </tr>
    </table>
    
    </fieldset>  
</div>




</div>  	
</body>
</html>