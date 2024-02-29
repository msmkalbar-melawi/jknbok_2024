 	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript">
     
    $(document).ready(function() 
	{ 
	get_skpd()
	}); 
    
	var idx=0;
	var tidx=0;
	var oldRek=0;
    var skpd='';
    var urusan='';
        
        
    $(function(){
        $('#urusan').combogrid({  
            panelWidth:700,  
            idField:'kd_urusan',  
            textField:'kd_urusan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/urusan',  
            columns:[[  
                {field:'kd_urusan',title:'Kode Urusan',width:100},  
                {field:'nm_urusan',title:'Nama Urusan',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                urusan = rowData.kd_urusan;
                $("#nm_urusan").attr("value",rowData.nm_urusan);
                //validate_skpd();
                validate_combo(skpd,urusan)
                validate_giat(skpd,urusan);
                
            }  
        }); 
      });
      
      function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#skpd").attr("value",data.kd_skpd);
        								$("#nm_skpd").attr("value",data.nm_skpd);
        								skpd = data.kd_skpd;
        							  }                                     
        	});  
        }
      
      function validate_skpd(){
		  $(function(){
            $('#skpd').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka/skpd',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                skpd = rowData.kd_skpd;
                $("#nm_skpd").attr("value",rowData.nm_skpd);
                validate_combo(skpd,urusan)
                validate_giat(skpd,urusan)
            }  
            }); 
            });
		}
        
        function validate_giat(){
		  $(function(){
            $('#giat').combogrid({  
            panelWidth:700,  
            idField:'kd_kegiatan',  
            textField:'kd_kegiatan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>/index.php/rka/ld_giat/'+skpd+'/'+urusan,  
            columns:[[  
                {field:'kd_kegiatan',title:'Kode Kegiatan',width:120},  
                {field:'nm_kegiatan',title:'Nama Kegiatan',width:600},
                {field:'jns_kegiatan',title:'Jenis Kegiatan',width:40},
                {field:'lanjut',title:'Jenis Kegiatan',width:40}
            ]],
            onSelect:function(rowIndex,rowData){
                    kode = rowData.kd_kegiatan;
                    nama = rowData.nm_kegiatan;
                    jenis = rowData.jns_kegiatan;
                    lanjut = rowData.lanjut;
                    $("#nm_giat").attr("value",rowData.nm_kegiatan);                    
                    append_jak();
                    simpan(kode,oldRek,jenis,lanjut); 
                    } 
            }); 
            });
		}
        												
        function append_jak(){
	$("#dg").edatagrid("selectAll");
        var rows = $("#dg").edatagrid("getSelections");
        var jrow = rows.length;
        jidx     = jrow + 1 ;			
		    //$('#dg').edatagrid('appendRow',{kd_rek5:reke,nm_rek5:nmrek5,nilai:nrek,id:jidx});
            $('#dg').edatagrid('appendRow',{kd_kegiatan:kode,id:jidx});
        }
         
        function validate_combo(skpd,urusan){
        var cskpd = document.getElementById('skpd').value;
        //alert(cskpd);
        //alert(skpd+urusan);
            $(function(){
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/select_giat/'+skpd,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
                 onAfterEdit:function(rowIndex, rowData, changes){								
								rk=rowData.kd_kegiatan;
								jns=rowData.jns_kegiatan;
								ljt=rowData.lanjut;

								simpan(rk,oldRek,jns,ljt);
							 },
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_kegiatan',
					 title:'Kegiatan',
					 width:30,
					 align:'left',	
					 editor:{type:"combobox",
      		                options:{valueField:'kd_kegiatan',
									  textField:'kd_kegiatan',
									  panelwidth:910,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_giat/'+skpd+'/'+urusan,                                   
									  required:true,
									  onSelect:function(){							
						                      oldRek=getSelections(getRowIndex(this));	
                                                //alert(oldRek);
						                  }
									  }
							}
					},                    
					{field:'nm_kegiatan',
					 title:'Nama Kegiatan',
					 width:140,
					 editor:{type:"text"}
					},
                    {field:'jns_kegiatan',
					 title:'Jenis',
					 width:10,
                     align:'center',
					 editor:{type:"combobox",
      		                options:{valueField:'jns_kegiatan',
									  textField:'jns_kegiatan',
									  panelwidth:910,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_jns',
									  required:true									  
									  }
							}
                    },
                    {field:'lanjut',
					 title:'Lanjutan',
					 width:20,
                     align:'center',
					 editor:{type:"combobox",
      		                options:{valueField:'lanjut',
									  textField:'lanjut',
									  panelwidth:910,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_lanjut',
									  required:true									  
									  }
							}
                    }
				]]
			});
		});
        }

    
	$(function(){
	   	    //var mskpd = document.getElementById('cc').value;           
            //var murusan = document.getElementById('ur').value;
            //var mskpd =skpd;           
            //var murusan =urusan;
            //alert(skpd+urusan);
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka/select_giat',
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
				 onAfterEdit:function(rowIndex, rowData, changes){								
								rk=rowData.kd_kegiatan;
								jns=rowData.jns_kegiatan;
								ljt=rowData.lanjut;

								simpan(rk,oldRek,jns,ljt);
							 },
				 onSelect:function(){							
						  oldRek=getSelections(getRowIndex(this));	
                            //alert(oldRek);
						  },
				columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_kegiatan',
					 title:'Kegiatan',
					 width:30,
					 align:'left',	
					 editor:{type:"combobox",
      		                options:{valueField:'kd_kegiatan',
									  textField:'kd_kegiatan',
									  panelwidth:910,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_giat',
									  required:true,
									  onSelect:function(){							
						                      oldRek=getSelections(getRowIndex(this));	
                                                //alert(oldRek);
						                  }
									  }
							}
					},                    
					{field:'nm_kegiatan',
					 title:'Nama Kegiatan',
					 width:140,
					 editor:{type:"text"}
					},
                    {field:'jns_kegiatan',
					 title:'Jenis',
					 width:10,
                     align:'center',
					 editor:{type:"combobox",
      		                options:{valueField:'jns_kegiatan',
									  textField:'jns_kegiatan',
									  panelwidth:910,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_jns',
									  required:true									  
									  }
							}
                    },
                    {field:'lanjut',
					 title:'Lanjutan',
					 width:20,
                     align:'center',
					 editor:{type:"combobox",
      		                options:{valueField:'lanjut',
									  textField:'lanjut',
									  panelwidth:910,	
									  url :'<?php echo base_url();?>/index.php/rka/ld_lanjut',
									  required:true									  
									  }
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
				ids.push(rows[i].kd_kegiatan);
			}
			return ids.join(':');
		}


		function getRowIndex(target){  
			var tr = $(target).closest('tr.datagrid-row');  
			return parseInt(tr.attr('datagrid-row-index'));  
		}  


        function simpan(baru,lama,jns,lanjut){		
		//var curus = document.getElementById('urusan').value;
		//var cskpd = document.getElementById('skpd').value;        
        if (lama==''){ lama=baru}
        //alert(urusan+skpd+baru+lama+jns);
            $(function(){
				$('#dg').edatagrid({
				     url: '<?php echo base_url(); ?>/index.php/rka/psimpan/'+skpd+'/'+urusan+'/'+baru+'/'+lama+'/'+jns+'/'+lanjut,
					 idField:'id',
					 toolbar:"#toolbar",              
					 rownumbers:"true", 
					 fitColumns:"true",
					 singleSelect:"true"
				});
			});

		}
		
		
        
        function hapus(){
				//var cgiat = document.getElementById('giat').value;
				//var cskpd = document.getElementById('skpd').value;
				var rek=getSelections();
                //alert(skpd+rek) 
				if (rek !=''){
				var del=confirm('Anda yakin akan menghapus kegiatan '+rek+' ?');
				if  (del==true){
					$(function(){
						$('#dg').edatagrid({
							 url: '<?php echo base_url(); ?>/index.php/rka/ghapus/'+skpd+'/'+rek,
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

<div id="content">   
 <!-- <?php echo $prev; ?>-->
  <h3>U R U S A N&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="urusan" name="urusan" style="width: 100px;" />&nbsp;&nbsp;&nbsp;<input id="nm_urusan" name="nm_urusan" readonly="true" style="width:200px;border: 0;"/> </h3>
  <h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="skpd" name="skpd" readonly="true" style="width: 100px;border: 0;" />&nbsp;&nbsp;&nbsp;<input id="nm_skpd" name="nm_skpd" readonly="true" style="width:600px;border: 0;"/> </h3>
  <h3>K E G I A T A N&nbsp;&nbsp;&nbsp;&nbsp;<input id="giat" name="giat" style="width: 150px;" />&nbsp;&nbsp;&nbsp;<input id="nm_giat" name="nm_giat" readonly="true" style="width:650px;border: 0;"/> </h3>
  
   <table id="dg" title="Pilih Kegiatan Anggaran" style="width:910%;height:300%" >  
        

	</table>   
<!--	
        <button type="button" onclick="javascript:$('#dg').edatagrid('addRow')">BARU</button>
        <button type="button" onclick="javascript:hapus()">HAPUS</button>       	
		<button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">SIMPAN</button>
		<button class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">BATAL</button>
	
	     -->
 
</div>  	

