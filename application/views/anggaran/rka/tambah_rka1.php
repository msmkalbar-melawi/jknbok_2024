<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 	<link rel="stylesheet" href="<?php echo base_url(); ?>tinybox/style.css" />
	<script type="text/javascript" src="<?php echo base_url(); ?>tinybox/tinybox.js"></script>  
  	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript">
     
	var nl =0;
	var tnl =0;
	var idx=0;
	var tidx=0;
	var oldRek=0;

	$(function(){
	   	    var mgiat = document.getElementById('giat').value;
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/select_rka',
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
				 onAfterEdit:function(rowIndex, rowData, changes){								
								rk=rowData.kd_rek5;
								nilai=rowData.nilai;
								simpan(rk,oldRek,nilai);
							 },
				 onSelect:function(){							
						  oldRek=getSelections(getRowIndex(this));	

						  },
				columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_rek5',
					 title:'Kode Rekening',
					 width:20,
					 align:'left',	
					 editor:{type:"combobox",
							 options:{valueField:'kd_rek5',
									  textField:'kd_rek5',
									  panelwidth:1000,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_rek/'+mgiat,
									  required:true,
									  onChange:function(newValue,oldValue){ 
													//if ( (oldRek =='') && (newValue.length==7) ){	
													//	oldRek=newValue;
													//	simpan(newValue,oldValue,nl);  														
													//}
											   }
									  }
							}
					},
					{field:'nm_rek5',
					 title:'Nama Rekening',
					 width:100,
					 editor:{type:"text"}
					},
                    {field:'nilai',
					 title:'Nilai Rekening',
					 width:20,
                     align:'right',
					 editor:{type:"numberbox",
						     options:{precision:0,groupSeparator:',',decimalSeparator:'.'}
							} 
                     },
					 {field:'rinci',
					  title:'Detail',
					  width:10,
					  align:'center', 
					  formatter:function(value,rec){
							return '<p ONCLICK="pop_detail('+rec.kd_rek5+')">Rincian</p>';
						}
					 }
				]]	
			
			});
  	
		  

		});




		function getSelections(idx){
			//alert(idx);
			var ids = [];
			var rows = $('#dg').edatagrid('getSelections');
			for(var i=0;i<rows.length;i++){
				ids.push(rows[i].kd_rek5);
			}
			return ids.join(':');
		}


		function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		}  


        function simpan(baru,lama,nilai){		
		var cgiat = document.getElementById('giat').value;
		var cskpd = document.getElementById('skpd').value;
		if (lama==''){
			lama=baru;
		}	
			$(function(){
				$('#dg').edatagrid({
				     url: '<?php echo base_url(); ?>/index.php/rka/tsimpan/'+cskpd+'/'+cgiat+'/'+baru+'/'+lama+'/'+nilai,
					 idField:'id',
					 toolbar:"#toolbar",              
					 rownumbers:"true", 
					 fitColumns:"true",
					 singleSelect:"true"
				});
			});

		}
		
														

        function validate_combo(){
        var cgiat = document.getElementById('giat').value;
            $(function(){
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/select_rka/'+cgiat,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true"
			});
		});
        }
        
		function hapus(){
				var cgiat = document.getElementById('giat').value;
				var cskpd = document.getElementById('skpd').value;
				var rek=getSelections(); 
				if (rek !=''){
				var del=confirm('Anda yakin akan menghapus rekening '+rek+' ?');
				if  (del==true){
					$(function(){
						$('#dg').edatagrid({
							 url: '<?php echo base_url(); ?>/index.php/rka/thapus/'+cskpd+'/'+cgiat+'/'+rek,
							 idField:'id',
							 toolbar:"#toolbar",              
							 rownumbers:"true", 
							 fitColumns:"true",
							 singleSelect:"true"
						});
					});
				
				}
				}
		}
    
	</script>

</head>
<body>

<script type="text/javascript">
	function pop_detail(rek){
	var mgiat = document.getElementById('giat').value;
	var mskpd = document.getElementById('skpd').value;
	TINY.box.show('<?php echo base_url(); ?>index.php/rka/rinci_rka/'+mskpd+'/'+mgiat+'/'+rek,1,900,550,1);
	}
</script>




<div id="content">
    
   <?php echo $prev; ?><br />
   <table id="dg" title="Input Rekening Rencana Kegiatan Anggaran" style="width:910%;height:350%" >  
        
   </table>  
   	    <!--<button type="button" onclick="javascript:$('#dg').edatagrid('addRow')">BARU</button>
        <button type="button" onclick="javascript:hapus()">HAPUS</button>-->
		<button class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">BARU</button>
		<button class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">HAPUS</button>
		<button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">SIMPAN</button>
		<button class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">BATAL</button>
    
 
</div>  	
</body>

</html>