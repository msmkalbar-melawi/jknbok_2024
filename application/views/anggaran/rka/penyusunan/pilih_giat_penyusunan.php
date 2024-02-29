 	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript">
     
    $(document).ready(function() 
	{ 
	//get_skpd()
	}); 
    
	var idx=0;
	var tidx=0;
	var oldRek=0;
    var skpd='';
    var urusan='';
        
        
    $(document).ready(function() {
    //     $('#urusan').combogrid({  
    //         panelWidth:700,  
    //         idField:'kd_urusan',  
    //         textField:'kd_urusan',  
    //         mode:'remote',
    //         url:'<?php echo base_url(); ?>index.php/rka_rancang/urusan',  
    //         columns:[[  
    //             {field:'kd_urusan',title:'Kode Urusan',width:100},  
    //             {field:'nm_urusan',title:'Nama Urusan',width:700}    
    //         ]],
    //         onSelect:function(rowIndex,rowData){
    //             urusan = rowData.kd_urusan;
    //             $("#nm_urusan").attr("value",rowData.nm_urusan);
    //            // validate_skpd();
				
				// //runEffect();
    //             //validate_combo(skpd,urusan)
    //             validate_giat(skpd,urusan);
                
    //         } 
    //     }); 
				   		
   		// $("#urusan").combogrid("disable");			


   		$('#giat').combogrid({  
            panelWidth:700,  
            idField:'kd_kegiatan',  
            textField:'kd_kegiatan',  
            mode:'remote',
            url:'<?php echo base_url(); ?>/index.php/rka_rancang/ld_giat_rancang/'+''+'/'+'',  
            columns:[[  
                {field:'kd_kegiatan',title:'Kode Sub Kegiatan',width:120},  
                {field:'nm_kegiatan',title:'Nama Sub Kegiatan',width:600},
                {field:'jns_kegiatan',title:'Jenis Kegiatan',width:40},
                {field:'lanjut',title:'Lanjut',width:40}
            ]],
            onSelect:function(rowIndex,rowData){
				//alert('on select ni bro...............')
                    kode = rowData.kd_kegiatan;
                    nama = rowData.nm_kegiatan;
                    jenis = rowData.jns_kegiatan;
                    lanjut = rowData.lanjut;
                    $("#nm_giat").attr("value",rowData.nm_kegiatan);   					
					 append_jak();

                    simpan(kode,oldRek,'',lanjut); 
                    }
					/* ,
                    onDblClickRow:function(rowIndex,rowData){
                        section2();   
                    } */ 
            }); 
	  
    //$(function(){
		$('#Xskpd').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/rka_rancang/skpd',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
				skpd = rowData.kd_skpd;
                $("#nm_skpd").attr("value",rowData.nm_skpd);

			    $("#giat").combogrid("clear");				
//			    $("#giat").combogrid("setValue",'');				
//				alert(skpd);
				runEffect();
				//validate_giat(skpd,urusan);
                validate_combo(skpd);
				$('#dg').edatagrid('reload');
            }  
            }); 
//});

	  //$(function(){
		

     //   });    
    });    
	 /*  function get_skpd()
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
        } */
      
      function validate_skpd(){
/* 		  $(function(){
            $('#Xskpd').combogrid({  
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
				//alert(skpd);
                $("#nm_skpd").attr("value",rowData.nm_skpd);				
				validate_giat(skpd,urusan);
                validate_combo(skpd,urusan);
				$('#dg').edatagrid('reload');
            }  
            }); 
            }); */
		}
        
        function validate_giat(skpd){
		 // $(function(){
		 	// alert(skpd+'----'+urusan);
			  if($("#st_lintas").is(':checked'))
				{	skpd='0';				}
			
			$('#giat').combogrid({  
            url:'<?php echo base_url(); ?>/index.php/rka_rancang/ld_giat_rancang/'+skpd
            });
			
		}


		// function get_urusan(){
		// 	zskpd= $("#Xskpd").combogrid("getValue");
		// 	// alert(zskpd);
		// 	// $('#urusan').combogrid({  
  //  //             panelWidth : 700,  
  //  //             idField    : 'kd_urusan',  
  //  //             textField  : 'kd_urusan',  
  //  //             mode       : 'remote',
  //  //             url        : '<?php echo base_url(); ?>index.php/rka_rancang/urusan1',  
  //  //             queryParams: ({skpd:zskpd}),
  //  //             columns    : [[  
  //  //                 	{field:'kd_urusan',title:'Kode Urusan',width:100},  
  //  //              	{field:'nm_urusan',title:'Nama Urusan',width:700}    
  //  //             ]],  
  //  //             onSelect:function(rowIndex,rowData){
  //  //                  urusan = rowData.kd_urusan;
	 //  //               $("#nm_urusan").attr("value",rowData.nm_urusan);
	 //  //              // validate_skpd();
					
		// 	// 		//runEffect();
	 //  //               //validate_combo(skpd,urusan)
	 //  //               validate_giat(skpd,urusan);
  //  //             }
  //  //           });  
		// }
        												
        function append_jak(){
	$("#dg").edatagrid("selectAll");
        var rows = $("#dg").edatagrid("getSelections");
        var jrow = rows.length;
        jidx     = jrow + 1 ;			
		    //$('#dg').edatagrid('appendRow',{kd_rek5:reke,nm_rek5:nmrek5,nilai:nrek,id:jidx});
            $('#dg').edatagrid('appendRow',{kd_kegiatan:kode,id:jidx});
        }
         
        function validate_combo(skpd){
        //var cskpd = document.getElementById('skpd').value;
        //alert(cskpd);
        //alert(skpd+urusan);
            $(function(){
			$('#dg').edatagrid({
				url: '<?php echo base_url(); ?>/index.php/rka_rancang/select_giat_rancang/'+skpd,
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
                 onAfterEdit:function(rowIndex, rowData, changes){								
								rk=rowData.kd_kegiatan;
								jns=rowData.jns_kegiatan;
								ljt=rowData.lanjut;

								simpan(rk,oldRek,'',ljt);
							 },
                 columns:[[
	                {field:'ck',
					 title:'ck',
					 checkbox:true,
					 hidden:true},
					{field:'kd_kegiatan',
					 title:'Kode Sub Kegiatan',
					 width:30,
					 align:'left',	
					 editor:{type:"combobox",
      		                options:{valueField:'kd_kegiatan',
									  textField:'kd_kegiatan',
									  panelwidth:910,	
									  url :'<?php echo base_url();?>/index.php/rka_rancang/ld_giat_rancang/'+skpd,                                   
									  required:true,
									  onSelect:function(){							
						                      oldRek=getSelections(getRowIndex(this));	
                                                //alert(oldRek);
						                  }
									  }
							}
					},                    
					{field:'nm_kegiatan',
					 title:'Nama Sub Kegiatan',
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
									  url :'<?php echo base_url();?>/index.php/rka_rancang/ld_jns',
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
									  url :'<?php echo base_url();?>/index.php/rka_rancang/ld_lanjut',
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
				url: '<?php echo base_url(); ?>/index.php/rka_rancang/select_giat_rancang',
                 idField:'id',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:"true",
                 singleSelect:"true",
				 onAfterEdit:function(rowIndex, rowData, changes){								
								rk=rowData.kd_kegiatan;
								jns=rowData.jns_kegiatan;
								ljt=rowData.lanjut;

								simpan(rk,oldRek,'',ljt);
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
					 title:'Kode Sub Kegiatan',
					 width:30,
					 align:'left',	
					 editor:{type:"combobox",
      		                options:{valueField:'kd_kegiatan',
									  textField:'kd_kegiatan',
									  panelwidth:910,	
									  url :'<?php echo base_url();?>/index.php/rka_rancang/ld_giat_rancang',
									  required:true,
									  onSelect:function(){							
						                      oldRek=getSelections(getRowIndex(this));	
                                                //alert(oldRek);
						                  }
									  }
							}
					},                    
					{field:'nm_kegiatan',
					 title:'Nama Sub Kegiatan',
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
									  url :'<?php echo base_url();?>/index.php/rka_rancang/ld_jns',
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
									  url :'<?php echo base_url();?>/index.php/rka_rancang/ld_lanjut',
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
        // alert(urusan+skpd+baru+lama+'-'+jns+'-'+lanjut);
            $(function(){
				$('#dg').edatagrid({
				     url: '<?php echo base_url(); ?>/index.php/rka_rancang/psimpan_rancang/',
				     queryParams: ({skpd:skpd,rekbaru:baru,reklama:lama,jenis:jns,lanjut:lanjut}),
					 idField:'id',
					 toolbar:"#toolbar",              
					 rownumbers:"true", 
					 fitColumns:"true",
					 singleSelect:"true"
				});
			});
		$('#dg').edatagrid('reload');
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
							 url: '<?php echo base_url(); ?>/index.php/rka_rancang/ghapus_rancang/'+skpd+'/'+rek,
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
        
		function runEffect(){
			zskpd= $("#Xskpd").combogrid("getValue");

			$("#giat").combogrid("clear");
			if($("#st_lintas").is(':checked'))
				{	
				// $("#urusan").combogrid("enable");
				// get_urusan();
				$("#Xskpd").combogrid("disable");
				//$("#urusan").combogrid("setValue",no_spd);
					validate_giat(zskpd);
				} else
				{	
					// var kurusan = Left(zskpd,4).replace('-','.');

					// $("#urusan").combogrid("setValue",kurusan);
					// $("#urusan").combogrid("disable");
					$("#Xskpd").combogrid("enable");
//					validate_giat(zskpd,Left(zskpd,4))
					validate_giat(zskpd)
				}
				
				
					
			
			
		}
        
	function Left(str, n){
    	if (n <= 0)
    	    return "";
    	else if (n > String(str).length)
    	    return str;
    	else
    	    return String(str).substring(0,n);
    }
     
    function Right(str, n){
        if (n <= 0)
           return "";
        else if (n > String(str).length)
           return str;
        else {
           var iLen = String(str).length;
           return String(str).substring(iLen, iLen - n);
        }
    }
    
	</script>
    
</head>
<body>

<div id="content">   
 <!-- <?php echo $prev; ?>-->
  <h3>	PILIH SUB KEGIATAN PENYUSUNAN <input type="checkbox" id="st_lintas"  onclick="javascript:runEffect();" hidden="true" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
  <h3>S K P D&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="Xskpd" name="Xskpd" style="width: 150px;border: 0;" />&nbsp;&nbsp;&nbsp;<input id="nm_skpd" name="nm_skpd" readonly="true" style="width:600px;border: 0;"/> </h3>
<!--   <h3>BIDANG URUSAN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="urusan" name="urusan" style="width: 150px;" />&nbsp;&nbsp;&nbsp;<input id="nm_urusan" name="nm_urusan" readonly="true" style="width:600px;border: 0;"/> </h3> -->
  
  <h3>SUB KEGIATAN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="giat" name="giat" style="width: 150px;" />&nbsp;&nbsp;&nbsp;<input id="nm_giat" name="nm_giat" readonly="true" style="width:600px;border: 0;"/> </h3>
  <font color="red"><p>Data akan tersimpan otomatis saat sub kegiatan dipilih</p></font>
   <table id="dg" title="Pilih Kegiatan Anggaran Penyusunan" style="width:910%;height:300%" >  
        

	</table>    	    
        <!-- <button type="button" onclick="javascript:$('#dg').edatagrid('addRow')">BARU</button>
		<button class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('addRow');">SIMPAN</button> 
		
		<button class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">BATAL</button>
		-->
        <button type="button" onclick="javascript:hapus()">HAPUS</button>       	
	
	     
 
</div>  	

