<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

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
                    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 540,
            width: 900,
            modal: true,
            autoOpen:false,
        });
         $( "#dialog-modal-rekening" ).dialog({
                height: 230,
                width: 900,
                modal: true,
                autoOpen:false                
        });             
        get_skpd();
		    get_tahun();
        });    
     
  
    
     
     $(function(){ 
     $('#dg').edatagrid({
        rowStyler:function(index,row){
          if ((row.ketup==1 && row.ketval==1)){
			  return 'background-color:#00a5ff;';
		  }else if ((row.ketup==1)){
			  return 'background-color:#12cc2e;';
		  }
        },
		    url: '<?php echo base_url(); ?>/index.php/ctambah_panjar_cms/load_tpanjar',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
    	    {field:'no_panjar',title:'Nomor Panjar',width:50,align:"center"},
          {field:'tgl_panjar',title:'Tanggal',width:30},
          {field:'kd_skpd',title:'S K P D',width:30,align:"center"},
          {field:'nilai',title:'Nilai',width:50,align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor = rowData.no_panjar;
          tgl   = rowData.tgl_panjar;
          nokas = rowData.no_kas;
          tglkas   = rowData.tgl_kas;
          kode  = rowData.kd_skpd;
          lcket = rowData.keterangan;
          lcnilai = rowData.nilai;
          kd_sub_kegiatan = rowData.kd_sub_kegiatan;
          lcpay = rowData.pay;
		      status = rowData.status;
          status_upload = rowData.ketup;
          lcrekening_awal = rowData.lcrekening_awal;
		      no_tambah = rowData.no_tambah;
          lcidx = rowIndex;
          get(nokas,tglkas,nomor,tgl,kode,lcket,lcnilai,lcpay,kd_sub_kegiatan,status,no_tambah,lcrekening_awal,status_upload);
          load_detail_rekbank(nokas,tglkas,kode);    
          set_grid5();                         
          load_sum_panjar();                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Tambah Panjar'; 
           edit_data();   
        }
        
        });

       $('#dg5').edatagrid({  
            toolbar:'#toolbar2',
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            autoRowHeight:"false",
            loadMsg:"Tunggu Sebentar....!!",            
            nowrap:"true",
            onSelect:function(rowIndex,rowData){                    
                    idx = rowIndex;     
                    nilx = rowData.nilai;               
            },                                                     
            columns:[[
            {field:'no_bukti',title:'No Bukti',hidden:"true"},
            {field:'tgl_bukti',title:'Tanggal',hidden:"true"},
            {field:'rekening_awal',title:'rekening awal',hidden:"true"},
            {field:'nm_rekening_tujuan',title:'Nama',width:10},
            {field:'rekening_tujuan',title:'Rek. Tujuan',width:10},
            {field:'bank_tujuan',title:'Bank',hidden:"true"},
            {field:'kd_skpd',title:'SKPD',hidden:'true'},
            {field:'nilai',title:'Nilai',width:10,align:"right"}          
            ]]
        });
        $('#tglpanjar').datebox({  
                required:true,
                formatter :function(date){
                  var y = date.getFullYear();
                  var m = date.getMonth()+1;
                  var d = date.getDate();    
                  return y+'-'+m+'-'+d;
                }
         });
        
         $('#tanggal').datebox({  
            required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });

    $('#rekening_awal').combogrid({            
        url:"<?php echo base_url(); ?>index.php/ctambah_panjar_cms/cari_rekening",
        panelWidth:150,
        idField:'rek_bend',
        textField:'rek_bend',
        columns:[[
            {field:'rek_bend',title:'Rekening Bendahara',width:130}
        ]]
    });

    $('#rekening_tujuan').combogrid({            
        url:"<?php echo base_url(); ?>index.php/ctambah_panjar_cms/cari_rekening_tujuan/1",
        panelWidth:730,
        idField:'rekening',
        textField:'rekening',
        mode:'remote',
        columns:[[
            {field:'rekening',title:'Rekening',width:120},
            {field:'nm_rekening',title:'Nama',width:290},
            {field:'ket',title:'Keterangan Tambahan',width:290}
        ]],
        onSelect:function(rowIndex,rowData){
        $("#nm_rekening_tujuan").attr("Value",rowData.nm_rekening);
        $("#kd_bank_tujuan").combogrid("setValue",rowData.nmbank);                
      }
    });

        
    $('#kd_bank_tujuan').combogrid({            
        url:"<?php echo base_url(); ?>index.php/ctambah_panjar_cms/cari_bank",
        panelWidth:150,
        idField:'nama',
        textField:'nama',
        columns:[[
            {field:'nama',title:'Bank',width:130}
        ]]
    });

		$('#no_tpanjar').combogrid({  
                   panelWidth : 460,  
                   idField    : 'no_panjar',  
                   textField  : 'no_panjar',  
                   mode       : 'remote',
                   url        : '<?php echo base_url(); ?>index.php/tukd/load_no_tpanjar',  
                   columns:[[  
                       {field:'no_panjar',title:'No. Panjar',width:100},  
                       {field:'tgl_panjar',title:'Tanggal',width:250},
					   {field:'nilai',title:'nilai',width:100}  
                   ]],  
                   onSelect:function(rowIndex,rowData){
				   $("#nilai_tpanjar").attr("value",number_format(rowData.nilai,2,'.',',')); 
				   no_tpanjar = rowData.no_panjar;               
				 $('#kd_giat').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ld_giat_tpanjar/'+no_tpanjar});                 
                   }  
                   });
		
		
		
		$('#kd_giat').combogrid({  
                   panelWidth : 650,  
                   idField    : 'kd_sub_kegiatan',  
                   textField  : 'kd_sub_kegiatan',  
                   mode       : 'remote',
                  // url        : '<?php echo base_url(); ?>index.php/tukd/ld_giat_panjar',  
                   columns:[[  
                       {field:'kd_sub_kegiatan',title:'Kode Kegiatan',width:170},  
                       {field:'nm_sub_kegiatan',title:'Nama Kegiatan',width:250},
					   {field:'transaksi',title:'Transkasi Lalu',width:100},  
                       {field:'anggaran',title:'Anggaran',width:100}    
                   ]],  
                   onSelect:function(rowIndex,rowData){
                   var lctunai = document.getElementById('jns_tunai').value; 
				   if(lctunai==''){
                        alert('Pilih Jenis Pembayaran Terlebih Dahulu');   
                        $("#kd_giat").combogrid("clear");
                        return;
                   }else{     
                        validate_jenis();
				        sisa_anggaran = (rowData.anggaran)-(rowData.transaksi);               
				        $("#nm_giat").attr("value",rowData.nm_sub_kegiatan); 
                        $("#sisa_ang").attr("value",number_format(sisa_anggaran,2,'.',',')); 
                   }
                   }  
                   });
		
		
		
        $('#tanggal_kas').datebox({  
            required:true,
                formatter :function(date){
                	var y = date.getFullYear();
                	var m = date.getMonth()+1;
                	var d = date.getDate();
                	return y+'-'+m+'-'+d;
                }
            });
    
        //$('#skpd').combogrid({  
//           panelWidth:700,  
//           idField:'kd_skpd',  
//           textField:'kd_skpd',  
//           mode:'remote',
//           url:'<?php echo base_url(); ?>index.php/tukd/skpd_2',  
//           columns:[[  
//               {field:'kd_skpd',title:'Kode SKPD',width:100},  
//               {field:'nm_skpd',title:'Nama SKPD',width:700}    
//           ]],  
//           onSelect:function(rowIndex,rowData){
//               kode = rowData.kd_skpd;               
//               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
//               $('#rek').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/ambil_rek_tetap/'+kode});                 
//           }  
//       });
         
                  
         

      
    });


    function set_grid5(){
        $('#dg5').edatagrid({                                                                
            columns:[[
            {field:'no_bukti',title:'No Bukti',hidden:"true"},
            {field:'tgl_bukti',title:'Tanggal',hidden:"true"},
          {field:'rekening_awal',title:'rekening awal',hidden:"true"},
            {field:'nm_rekening_tujuan',title:'Nama',width:10},
            {field:'rekening_tujuan',title:'Rek. Tujuan',width:10},
            {field:'bank_tujuan',title:'Bank',hidden:"true"},
            {field:'kd_skpd',title:'SKPD',hidden:'true'},
            {field:'nilai',title:'Nilai',width:10,align:"right"}          
            ]]
        });                  
    }

    function load_detail_rekbank(nokas,tglkas,kode){
        var kk = nokas;        
        var ctgl = tglkas;
        var cskpd = kode;             
         
           $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/cpanjar_cms/load_dtrpanjar_transfercms',
                data: ({no:kk,skpd:cskpd}),
                dataType:"json",
                success:function(data){                                        
                    $.each(data,function(i,n){                                    
                    no      = n['no_bukti'];
                    tgl     = n['tgl_bukti'];                                                                    
                    rekawal = n['rekening_awal'];
                    nmrektj = n['nm_rekening_tujuan'];
                    rektj   = n['rekening_tujuan'];
                    bank    = n['bank_tujuan'];  
                    kd_skpd = n['kd_skpd'];                  
                    nilai   = n['nilai'];  
                    $("#total_dtransfer").attr("value",n['total']);                                                                                                                                       
                    $('#dg5').edatagrid('appendRow',{no_bukti:no,tgl_bukti:tgl,rekening_awal:rekawal,nm_rekening_tujuan:nmrektj,rekening_tujuan:rektj,bank_tujuan:bank,kd_skpd:kd_skpd,nilai:nilai});                                                                                                                                                                                                                                                                                                                                                                                             
                    });                                                                           
                }
            });
           });                        

         $('body').ajaxComplete(function () {           
            var total_belanja = angka(document.getElementById('nilai').value);
            var total_transfer = angka(document.getElementById('total_dtransfer').value);
            var hasil = total_belanja - total_transfer;        
            $("#nil_pot2").attr("value",number_format(hasil,2)); 
         });
         set_grid5();  
                                                           
    }
    
    
     function load_sisa_bank(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/load_sisa_bank",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#sisa_bank").attr("value",n['sisa']);
                   // $("#rekspm1").attr("value",n['rekspm1']);
                });
            }
         });
        });
    }

    function validate_jenis(){
           var lctunai = document.getElementById('jns_tunai').value;
           if(lctunai=='TUNAI'){        			 
    
            }else{
                load_sisa_bank();
            }            
    } 



    function get_skpd()
        {
        
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        								$("#skpd").attr("value",data.kd_skpd);
        								$("#nmskpd").attr("value",data.nm_skpd);
        								kode = data.kd_skpd;
                                        
        							  }                                     
        	});
             
        }
	function get_tahun() {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/tukd/config_tahun',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        			tahun_anggaran = data;
        			}                                     
        	});
             
        }		

     function section2(){
         $(document).ready(function(){    
             $('#section2').click();                                               
         });   
     }

    
    function section1(){
         $(document).ready(function(){    
             $('#section1').click();   
             $('#dg').edatagrid('reload');                                              
         });
     }
    
  
    function get(nokas,tglkas,nomor,tgl,kode,lcket,lcnilai,lcpay,kd_kegiatan,status,no_tambah,lcrekening_awal,status_upload){

	   $("#no_kas").attr("value",nokas);
        $("#no_simpan").attr("value",nokas);
        $("#tanggal_kas").datebox("setValue",tglkas);
        $("#nomor").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        $("#rekening_awal").combogrid("setValue",lcrekening_awal); 
        $("#nilai").attr("value",lcnilai);
        $("#ket").attr("value",lcket);
        $("#jns_tunai").attr("value",lcpay);
        $("#kd_giat").combogrid("setValue",kd_kegiatan);
        $("#no_tpanjar").combogrid("setValue",no_tambah);
		if (status_upload=='1'){
			$('#save').linkbutton('disable');
			$('#del').linkbutton('disable');
			document.getElementById("p1").innerHTML="   Sudah di Upload!!";
			} else {
			 $('#save').linkbutton('enable');
			 $('#del').linkbutton('enable');
			document.getElementById("p1").innerHTML="";
			}
                
    }
    
    function kosong(){
        $("#no_kas").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#tanggal_kas").datebox("setValue",'');
        $("#nomor").attr("value",'');
        $("#total").attr("value",'');
        $("#nilai_tpanjar").attr("value",'');
        $("#nm_giat").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#kd_giat").combogrid("setValue",'');
        $("#no_tpanjar").combogrid("setValue",'');
        //$("#nmskpd").attr("value",'');
        $("#total_dtransfer").attr("value",'0'); 
        $("#nilai").attr("value",'');        
        $("#ket").attr("value",''); 
        $("#jns_tunai").attr("value",'');
        $("#rekening_awal").combogrid("setValue",'');        
        $("#nm_rekening_tujuan").attr("Value",'');
        $("#kd_bank_tujuan").combogrid("setValue",'');        
        $("#rekening_tujuan").combogrid("setValue",'');
        $("#nil_pot2").attr("value",'0'); 
        $("#nilpotongan").attr("value",'0'); 
		document.getElementById("p1").innerHTML=" ";
		get_nourut();
    }
    
	function get_nourut()
        {
        	$.ajax({
        		url:'<?php echo base_url(); ?>index.php/ctambah_panjar_cms/no_urut_cms',
        		type: "POST",
        		dataType:"json",                         
        		success:function(data){
        							// $("#no_kas").attr("value",data.no_urut);
        								$("#nomor").attr("value",data.no_urut);
        							  }                                     
        	});  
        }
	
	
    function cari(){
    var kriteria = $('#tglpanjar').datebox('getValue');
    $(function(){ 
     $('#dg').edatagrid({
		url: '<?php echo base_url(); ?>/index.php/ctambah_panjar_cms/load_tpanjar_tgl',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
	 function load_sum_panjar(){                
        var nomor   = $("#no_tpanjar").combogrid("getValue") ; 
			//alert(nokas);
        $(function(){      
         $.ajax({
            type      : 'POST',
            data      : ({no:nomor}),
            url       : "<?php echo base_url(); ?>index.php/tukd/load_sum_tpanjar",
            dataType  : "json",
            success   : function(data){ 
                $.each(data, function(i,n){
                    $("#total").attr("value",number_format(n['rektotal1'],2,'.',','));
                    //$("#totalrekpajak").attr("value",n['rektotal']);
                });
            }
         });
        });
    }
    
    
    function simpan_tetap(){
        var cno_kas = document.getElementById('nomor').value;
        var no_simpan = document.getElementById('no_simpan').value;
        var ctgl_kas = $('#tanggal').datebox('getValue');
        var cno = document.getElementById('nomor').value;
        var ctgl = $('#tanggal').datebox('getValue');
        var ckd_giat   = $("#kd_giat").combogrid("getValue") ; 
        var cno_tpanjar   = $("#no_tpanjar").combogrid("getValue") ; 
        var cskpd = document.getElementById('skpd').value;//$('#skpd').combogrid('getValue');
        var lcket = document.getElementById('ket').value;
        var lctunai = document.getElementById('jns_tunai').value;
        var lntotal = angka(document.getElementById('nilai').value);
        var sisa_ang = angka(document.getElementById('sisa_ang').value);
        var sisa_bnk = angka(document.getElementById('sisa_bank').value);
        var nil_pot2 = angka(document.getElementById('nil_pot2').value);
        var total_transf = angka(document.getElementById('total_dtransfer').value);

        var tahun_input = ctgl.substring(0, 4);
        
        if (tahun_input != tahun_anggaran){
          alert('Tahun tidak sama dengan tahun Anggaran');
          exit();
        }

        if (total_transf!=lntotal-nil_pot2){
          alert('Nilai Transfer Tidak Sama Dengan Nilai Panjar + Pajak');
                exit();
        }
        
        if (total_transf==0){
          alert('Nilai Transfer Tidak Boleh Nol');
          exit();
        }
        
        if (sisa_bnk<lntotal && sisa_ang<lntotal){
          alert('Tidak boleh melebihi sisa Kas Bank dan Anggaran');
                exit();
        }
		
        if (cno==''){
            alert('Nomor  Tidak Boleh Kosong');
            exit();
        } 
        if (ctgl==''){
            alert('Tanggal  Tidak Boleh Kosong');
            exit();
        }

        if (cskpd==''){
            alert('Kode SKPD Tidak Boleh Kosong');
            exit();
        }

        if (ckd_giat==''){
            alert('Kegiatan Tidak Boleh Kosong');
            exit();
        }

        //alert(lcstatus)

        var crekawal = $('#rekening_awal').combogrid('getValue');
         cnmrekawal = document.getElementById('nm_rekening_tujuan').value;
        var crektujuan = $("#rekening_tujuan").combogrid("getValue"); 
        var cbanktujuan = $('#kd_bank_tujuan').combogrid('getValue');            
        var cket_tjuan = "TPNJR.KEG."+ckd_giat;
       
        var cek_rek = '0';
        var data = $('#dg5').datagrid('getData');
        var rows = data.rows;

        for (i=0; i < rows.length; i++) {
            if(rows[i].rekening_awal!=''){
                var cek_rek = '1';
            }
        }        
        if(cek_rek!='1'){
            alert('Isian Rekening Belum Lengkap!');
            return;
        }

       if(lcstatus == 'tambah'){
		$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'tr_panjar_cmsbank',field:'no_panjar'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1){
						alert("Nomor Telah Dipakai!");
						document.getElementById("nomor").focus();
						exit();
						} 
						if(status_cek==0){
						alert("Nomor Bisa dipakai");
                 //-------   
                    lcinsert = "(no_kas,tgl_kas,no_panjar,tgl_panjar,kd_skpd,pengguna,nilai,keterangan,pay,rek_bank,kd_sub_kegiatan,status,jns,no_panjar_lalu,rekening_awal,ket_tujuan,status_validasi,status_upload)";
                    lcvalues = "('"+cno_kas+"','"+ctgl_kas+"','"+cno+"','"+ctgl+"','"+cskpd+"','','"+lntotal+"','"+lcket+"','"+lctunai+"','','"+ckd_giat+"','0','2','"+cno_tpanjar+"','"+crekawal+"','"+cket_tjuan+"','0','0')";
        
                    $('#dg5').datagrid('selectAll');
                    var rows_rek = $('#dg5').datagrid('getSelections');   
                    for(var j=0;j<rows_rek.length;j++){
                    lnobukti   = cno;
                    lnotgl    = rows_rek[j].tgl_bukti;                                                             
                    lrekawal    = rows_rek[j].rekening_awal;
                    lnmrektj    = rows_rek[j].nm_rekening_tujuan;
                    lrektj       = rows_rek[j].rekening_tujuan;
                    lbank      = rows_rek[j].bank_tujuan;
                    lskpd      = rows_rek[j].kd_skpd; 
                    lnilai     = angka(rows_rek[j].nilai);                               
                
                    if (j>0) {
                        csql_rek = csql_rek+","+"('"+cno_kas+"','"+ctgl_kas+"','"+lrekawal+"','"+lnmrektj+"','"+lrektj+"','"+lbank+"','"+lskpd+"','"+lnilai+"')";
                    } else {
                        csql_rek = "values('"+cno_kas+"','"+ctgl_kas+"','"+lrekawal+"','"+lnmrektj+"','"+lrektj+"','"+lbank+"','"+lskpd+"','"+lnilai+"')";                                            
                    }                                             
                    } 


                    $(document).ready(function(){
                        $.ajax({
                            type: "POST",
                            url: '<?php echo base_url(); ?>/index.php/ctambah_panjar_cms/simpan_master_tpanjar',
                            data: ({tabel:'tr_panjar_cmsbank',kolom:lcinsert,nilai:lcvalues,cid:'no_panjar',lcid:cno,sqlrek:csql_rek}),
                            dataType:"json",
                            success:function(data){
                                status = data;
                                if (status=='0'){
                                    alert('Gagal Simpan..!!');
                                    exit();
                                }else if(status=='1'){
                                    alert('Data Sudah Ada..!!');
                                    exit();
                                }else{
                                    alert('Data Tersimpan..!!');
									lcstatus='edit';
									$("#no_simpan").attr("value",cno);
									$('#dg').edatagrid('reload');
                                    exit();
                                }
                            }
                        });
                    });    
                 //-------
                 }
		}
		});
		});
		
        
            
        } else {
//alert(z);
			$(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:cno,tabel:'tr_panjar_cmsbank',field:'no_panjar'}),
                    url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
						if(status_cek==1 && cno!=no_simpan){
						alert("Nomor Telah Dipakai!");
						exit();
						} 
						if(status_cek==0 || cno==no_simpan){
						alert("Nomor Bisa dipakai");
			
			
		//---------
                    
                    lcquery = "UPDATE tr_panjar_cmsbank SET no_panjar ='"+cno+"',no_kas='"+cno_kas+"',tgl_kas='"+ctgl_kas+"',tgl_panjar='"+ctgl+"',keterangan='"+lcket+"',nilai='"+lntotal+"',pay='"+lctunai+"',no_panjar_lalu='"+cno_tpanjar+"',kd_sub_kegiatan='"+ckd_giat+"',rekening_awal='"+crekawal+"',nm_rekening_tujuan='"+cnmrekawal+"',rekening_tujuan='"+crektujuan+"',bank_tujuan='"+cbanktujuan+"',ket_tujuan='"+cket_tjuan+"' where no_panjar='"+no_simpan+"' AND kd_skpd='"+cskpd+"'";
                    //alert(lcquery);

                    $('#dg5').datagrid('selectAll');
                    var rows_rek = $('#dg5').datagrid('getSelections');   
                    for(var j=0;j<rows_rek.length;j++){
                    lnobukti   = cno;
                    lnotgl    = rows_rek[j].tgl_bukti;                                                             
                    lrekawal    = rows_rek[j].rekening_awal;
                    lnmrektj    = rows_rek[j].nm_rekening_tujuan;
                    lrektj       = rows_rek[j].rekening_tujuan;
                    lbank      = rows_rek[j].bank_tujuan;
                    lskpd      = rows_rek[j].kd_skpd; 
                    lnilai     = angka(rows_rek[j].nilai);                               

                    if (j>0) {
                        csql_rek = csql_rek+","+"('"+cno_kas+"','"+ctgl_kas+"','"+lrekawal+"','"+lnmrektj+"','"+lrektj+"','"+lbank+"','"+lskpd+"','"+lnilai+"')";
                    } else {
                        csql_rek = "values('"+cno_kas+"','"+ctgl_kas+"','"+lrekawal+"','"+lnmrektj+"','"+lrektj+"','"+lbank+"','"+lskpd+"','"+lnilai+"')";                                            
                    }                                             
                    } 


                    $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/cpanjar_cms/update_master2',
                        data: ({st_query:lcquery,sqlrek:csql_rek,lcid:cno,xskpd:cskpd}),
                        dataType:"json",
                        success:function(data){
                                status = data;
                                if (status=='0'){
                                    alert('Gagal Simpan..!!');
                                    exit();
                                }else{
                                    alert('Data Tersimpan..!!');
									$("#no_simpan").attr("value",cno);
									$('#dg').edatagrid('reload');
                                    exit();
                                }
                            }
                    });
                    });
		//-----
				}
			}
			});
		});
        }
        
        //alert("Data Berhasil disimpan");
       
        //section1();
    } 
    
      function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Tambah Panjar';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        //document.getElementById("nomor").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Tambah Panjar';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=false;
        document.getElementById("nomor").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
     }    
    
     function hapus(){
      //  var cnomor = document.getElementById('nomor').value;
//        var cskpd = $('#skpd').combogrid('getValue');
        
        
        alert(nomor+kode);
        var urll = '<?php echo base_url(); ?>index.php/ctambah_panjar_cms/hapus_panjar_cmsbank';
        $(document).ready(function(){
         $.post(urll,({no:nomor,skpd:kode}),function(data){
            status = data;
            if (status=='0'){
                alert('Gagal Hapus..!!');
                exit();
            } else {
                $('#dg').datagrid('deleteRow',lcidx);   
                alert('Data Berhasil Dihapus..!!');
                exit();
            }
         });
        });    
    } 
    
    function cetak_list(){      
      var kriteria = $('#tglpanjar').datebox('getValue'); 
      if(kriteria==''){alert('Tanggal Tidak Boleh Kosong'); exit();}
      
      var url = '<?php echo site_url(); ?>/cpanjar_cms/cetak_list_panjarcms';
      window.open(url+'/'+kriteria+'/LIST PANJAR '+kriteria, '_blank');
      window.focus();        
    }

    function addCommas(nStr)
    {
    	nStr += '';
    	x = nStr.split(',');
        x1 = x[0];
    	x2 = x.length > 1 ? ',' + x[1] : '';
    	var rgx = /(\d+)(\d{3})/;
    	while (rgx.test(x1)) {
    		x1 = x1.replace(rgx, '$1' + '.' + '$2');
    	}
    	return x1 + x2;
    }
    
     function delCommas(nStr)
    {
    	nStr += ' ';
    	x2 = nStr.length;
        var x=nStr;
        var i=0;
    	while (i<x2) {
    		x = x.replace(',','');
            i++;
    	}
    	return x;
    }

    function tambah_rekening_bnk(){               
        kosong5();
        $("#dialog-modal-rekening").dialog('open');  
        set_grid5();
    }

    function kosong5(){             
        $("#nilai_trf").attr("value",'0'); 
        $("#nm_rekening_tujuan").attr("Value",'');
        $("#kd_bank_tujuan").combogrid("setValue",'');        
        $("#rekening_tujuan").combogrid("setValue",''); 
    }	

    function append_save_rekening(){
        var no    = document.getElementById('nomor').value;
        var ctgl = $('#tanggal').datebox('getValue'); 
        var cskpd = document.getElementById('skpd').value;
        var crekawal = $('#rekening_awal').combogrid('getValue');
        var cnmrekawal = document.getElementById('nm_rekening_tujuan').value;
        var crektujuan = $("#rekening_tujuan").combogrid("getValue").trim(); 
        var cbanktujuan = $('#kd_bank_tujuan').combogrid('getValue');
        var total_bel = angka(document.getElementById('nilai').value);
        var total_trf = angka(document.getElementById('total_dtransfer').value);    
        var nilai_trf = angka(document.getElementById('nilai_trf').value);
        var nilai_trff= document.getElementById('nilai_trf').value;
        var nil_pot= angka(document.getElementById('nilpotongan').value);
         
        var hasil_akmulasi = total_bel;
        var akumulasi = total_trf+nilai_trf;

        $('#dg5').datagrid('selectAll');
        var rows = $('#dg5').datagrid('getSelections');     
        for(var p=0;p<rows.length;p++){
            crektabel    = rows[p].rekening_tujuan;
            if (crektabel==crektujuan){                        
                var cdouble = 1;
            }                                                                                                                                                                             
        }             
        
        if(cdouble==1){
            alert('Rekening Tujuan '+crektujuan+' sdh ada. Silakan pilih Rekening Tujuan Lain');
            return;    
        }

       if(nilai_trf==0){
        alert("Nilai Tidak Boleh Nol");
                 exit();
    }
       
       if(akumulasi>hasil_akmulasi){
            alert('Nilai Melebihi Total Belanja');
            exit();
       }
        
       if(nilai_trf>hasil_akmulasi){
            alert('Nilai Melebihi Total Belanja');
            exit();
        }
        
       if(total_trf>hasil_akmulasi){
            alert('Nilai Melebihi Total Belanja');
            exit();
        } 
            
       if (crekawal==''){
                 alert('Pilih Rekening Sumber') ;
                 exit();
        } 
       if (cnmrekawal==''){
                 alert('Pilih rekening') ;
                 exit();
        }
       if (crektujuan==''){
                 alert('Pilih rekening') ;
                 exit();
        }
     if (cbanktujuan==''){
                 alert('Pilih rekening') ;
                 exit();
        }      
                      
                $('#dg5').edatagrid('appendRow',{no_bukti:no,
                                                 tgl_bukti:ctgl,
                                                 rekening_awal:crekawal,
                                                 nm_rekening_tujuan:cnmrekawal,
                                                 rekening_tujuan:crektujuan,
                                                 bank_tujuan:cbanktujuan,
                                                 kd_skpd:cskpd,
                                                 nilai:nilai_trff});                                                 
                
                total = angka(document.getElementById('total_dtransfer').value) + nilai_trf;                              
                $('#total_dtransfer').attr('value',number_format(total,2,'.',','));
                $('#nil_pot2').attr('value',number_format(nil_pot,2,'.',','));

    } 

    function tutup_rekening_bnk(){
        $("#dialog-modal-rekening").dialog('close');          
    } 

    function hapus_rekening_bnk(){
         var dtot_rekening = 0;      
           
         var tot = angka(document.getElementById('total_dtransfer').value);
         $('#dg5').datagrid('deleteRow',idx);
         
     $('#dg5').datagrid('selectAll');
            var rows_rek = $('#dg5').datagrid('getSelections');           
      for(var j=0;j<rows_rek.length;j++){
      dnilai     = angka(rows_rek[j].nilai);
            dtot_rekening = dtot_rekening + dnilai;
      } 
                  
         $('#total_dtransfer').attr('value',number_format(dtot_rekening,2,'.',','));              
    }
	
	function hitung(){   
        var nilai = angka(document.getElementById('nilai').value);
        var nilai_tpanjar = angka(document.getElementById('nilai_tpanjar').value);
       var total =nilai+nilai_tpanjar;
	$("#total").attr("value",number_format(total,2,'.',',')); 
       
     }
	
  
    
  
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">INPUTAN TAMBAH PANJAR CMS</a></b></u></h3>
    <div>
    <p align="right">         
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>               
        <a id="del" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
        &nbsp;| &nbsp;
        Tanggal
        <input name="tglpanjar" type="text" id="tglpanjar" style="width:100px; border: 0;"/>
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <table id="dg" title="Listing data tambah panjar" style="width:870px;height:450px;" >  
        </table>
 
    </p> 
    </div>   

</div>

</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
   <p id="p1" style="font-size:medium;color: red;"></p>
    <fieldset>
     <table align="center" style="width:100%;">
			<tr>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"><i>No. Tersimpan<i></td>
                <td style="border-bottom: double 1px red;border-right-style:hidden;border-top: double 1px red;"></td>
				<td style="border-bottom: double 1px red;border-top: double 1px red;"><input type="text" id="no_simpan" style="border:0;width: 200px;" readonly="true";/> &nbsp;&nbsp;<i>Tidak Perlu diisi atau di Edit</i></td>
                    
            </tr>
	 
             <tr>
                <td>No. Tambah Panjar</td>
                <td></td>
                <td><input type="text" id="nomor" style="width: 200px;"/></td>  
            </tr>             
            <tr>
                <td>Tanggal </td>
                <td></td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>
            </tr>
            <tr>
                <td>S K P D</td>
                <td></td>
                <td><input id="skpd" name="skpd" style="width: 140px;" /> &nbsp;&nbsp; <input type="text" id="nmskpd" style="border:0;width: 400px;" readonly="true"/></td>                            
            </tr>
             <tr>
                <td>Pembayaran</td>
                <td></td>
                 <td>
                     <select name="jns_tunai" id="jns_tunai" onchange="javascript:validate_jenis();">
                         <option value="BANK">BANK</option>   
                     </select>
                     Rek. Bank Bendahara &nbsp; <input type="text" id="rekening_awal" style="border:0;width: 150px;" readonly="true"/>
                 </td>
            </tr>


			     <tr>
                <td>No. Panjar </td>
                <td></td>
                <td><input id="no_tpanjar" name="no_tpanjar" style="width: 160px;" /> &nbsp;&nbsp; Nilai : &nbsp; <input type="text" id="nilai_tpanjar" style="border:0;width: 400px;" readonly="true"/></td>                            
            </tr>

            <tr>
                <td>Kegiatan</td>
                <td></td>
                <td><input id="kd_giat" name="kd_giat" style="width: 160px;" /> &nbsp;&nbsp; <input type="text" id="nm_giat" style="border:0;width: 400px;" readonly="true"/></td>                            
            </tr>  
			<tr>
                <td>Sisa Anggaran</td>
                <td></td>
                <td><input type="text" id="sisa_ang" style="border:0;width: 160px; text-align: right;"/></td> 
            </tr>
			<tr>
                <td>Sisa Bank</td>
                <td></td>
                <td><input type="text" id="sisa_bank" value="0.00" style="border:0;width: 160px; text-align: right;"/></td> 
            </tr>
			
            <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" style="width: 160px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:hitung();"/></td> 
            </tr>
			     <tr>
                <td>Total</td>
                <td></td>
                <td><input type="text" id="total" style="border:0;width: 160px; text-align: right;" /> </td> 
            </tr>
            <tr>
                <td>Pajak</td>
                <td></td>
                <td><input type="text" id="nil_pot2" style="width: 160px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" disabled="true" /></td> 
            </tr>			
            <tr>
                <td>Keterangan</td>
                <td colspan="2"><textarea rows="2" cols="50" id="ket" style="width: 740px;"></textarea>
                </td> 
            </tr>
           
            <tr>
                <td colspan="3" align="center"><a id="save" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_tetap();">Simpan</a>
		        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
        <table id="dg5" title="Daftar Rekening Tujuan" style="width:830px;height:180px;" >  
        </table>  
        <div id="toolbar2" align="right">
        <a id="tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah_rekening_bnk();">Tambah</a>
          <a id="hapus" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus_rekening_bnk();">Hapus</a>                   
        </div>
        <table align="center" style="width:100%;" border="0">
        <tr>            
            <td width="50%">&nbsp;</td>
            <td align="right">Total Transfer&nbsp;</td>
            <td align="center" width="27%">:&nbsp;<input type="text" id="total_dtransfer" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true"/> </td>
            <td align="right">&nbsp;</td>
        </tr>        
        </table>

    </fieldset>
</div>

<div id="dialog-modal-rekening" title="Input Rekening Tujuan *)Semua Inputan Harus Di Isi.">    
    <fieldset>
    <table>              
        <tr>
            <td>Rekening Tujuan</td>
            <td>:</td>
            <td><input type="text" id="rekening_tujuan" style="border:0;width: 200px;" />
            <td width="30%">&nbsp;</td>
            </td>            
        </tr>  
        <tr>
            <td>A.N. Rekening</td>
            <td>:</td>
            <td><input type="text" id="nm_rekening_tujuan" style="border:0;width: 400px;"/>
            <td width="30%">&nbsp;</td>
            </td>            
        </tr>
        <tr>
            <td>Bank</td>
            <td>:</td>
            <td><input type="text" id="kd_bank_tujuan" style="border:0;width: 200px;" onkeyup="javascript:cek_huruf(this);"/>
            <td width="30%">&nbsp;</td>
            </td>            
        </tr>          
        <tr bgcolor="#FFE4E1">
            <td>Nilai Total Potongan</td>
            <td>:</td>
            <td ><input id="nilpotongan" name="nilpotongan" style="text-align: right; width: 200px;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>            
            <td width="30%">&nbsp;*) Harus diisi jika ada potongan</td>
        </tr>        
        <tr>
            <td >Nilai Transfer</td>
            <td>:</td>
            <td><input type="text" id="nilai_trf" style="text-align: right; width: 200px;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>            
            <td width="30%">&nbsp;</td>
        </tr>
    </table>  
    </fieldset>
    <fieldset>
    <table align="center">
        <tr>
            <td><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save_rekening();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:tutup_rekening_bnk();">Keluar</a>                               
            </td>
        </tr>
    </table>   
    </fieldset>      
</div>
    
</body>

</html>