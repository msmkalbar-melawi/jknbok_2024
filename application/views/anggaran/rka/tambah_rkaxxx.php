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
	<script type="text/javascript" src="http://www.jeasyui.com/easyui/datagrid-detailview.js"></script> 
    <script type="text/javascript">
     
	var nl =0;
	var tnl =0;
	var idx=0;
	var tidx=0;
	var oldRek=0;
	var rekNow=0;

	$(function(){
	   	    var mgiat = document.getElementById('giat').value;
			var mskpd = document.getElementById('skpd').value;

			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/select_rka',
                 idField:'id',				 
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
				 view: detailview,
                 detailFormatter:function(index,row){
								       return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';
								 },
 				 onExpandRow: function(index,row){
					$('#ddv-'+index).datagrid({
						url:'<?php echo base_url(); ?>index.php/rka/rinci_rka/'+row.itemid,
						fitColumns:true,
						singleSelect:true,
						height:'auto',
						columns:[[
							{field:'uraian',title:'Uraian',width:200},
							{field:'volume1',title:'Volume',width:30},
						]],
						onResize:function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						},
						onLoadSuccess:function(){
							setTimeout(function(){
								$('#dg').datagrid('fixDetailRowHeight',index);
							},0);
						},
						onSelect:function(rowIndex, rowData, changes){								
										urai=rowData.uraian;
										alert(oldRek+'|'+urai);
										//simpan(rk,oldRek,nilai);
									 }
					});
					$('#dg').datagrid('fixDetailRowHeight',index);
				 },


				 onAfterEdit:function(rowIndex, rowData, changes){								
								rk=rowData.kd_rek5;
								nilai=rowData.nilai;
								rekNow=getSelections(getRowIndex(this));	
								simpan(rk,oldRek,nilai);
							 },
				 onSelect:function(){							
						  oldRek=getSelections(getRowIndex(this));	
						  rekNow=oldRek;
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
									  required:true
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
							return '<a href="#" style="color:blue" ONCLICK="pop_detail('+rec.kd_rek5+')">Rincian</a>';
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

	function pop(rek){
		var mgiat = document.getElementById('giat').value;
		var mskpd = document.getElementById('skpd').value;
        window.open('<?php echo base_url(); ?>index.php/rka/rinci_rka/'+mskpd+'/'+mgiat+'/'+rek,rek,'menubar=no,type=fullWindow,fullscreen,toolbar=no,location=no,resizable=no,scrollbars=no');
	}

</script>


    
   <?php echo $prev; ?><br />
   <table id="dg" title="Input Rekening Rencana Kegiatan Anggaran" style="width:1000%;height:500%" >  
        
   </table>  
   	<div id="toolbar">
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">New</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Destroy</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">Save</a>
		<a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel</a>
	</div>      
 
</body>

</html>