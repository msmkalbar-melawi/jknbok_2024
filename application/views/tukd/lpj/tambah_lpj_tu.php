<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
   
    var no_lpj   = '';
    var kode     = '';
    var spd      = '';
    var st_12    = 'edit';
    var nidx     = 100
    var spd2     = '';
    var spd3     = '';
    var spd4     = '';
    var lcstatus = 'tambah';
    var tahun_anggaran= "<?php echo $this->session->userdata('pcThang'); ?>";

    $(document).ready(function() {
            $("#accordion").accordion();
            $("#lockscreen").hide();                        
            $("#frm").hide();
            $( "#dialog-modal").dialog({
            height: 400,
            width: 700,
            modal: true,
            autoOpen:false
        });
        $( "#dialog-modal-tr").dialog({
            height: 320,
            width: 500,
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
   
    
    $(function(){

         $('#dd').datebox({  
            required:true,
            formatter :function(date){
              var y = date.getFullYear();
              var m = date.getMonth()+1;
              var d = date.getDate();
              return y+'-'+m+'-'+d;
            }
        });
        
        
        $('#dd1').datebox({  
            required:true,
            formatter :function(date){
              var y = date.getFullYear();
              var m = date.getMonth()+1;
              var d = date.getDate();
              return y+'-'+m+'-'+d;
            }
        });
        
        
        $('#dd2').datebox({  
            required:true,
            formatter :function(date){
              var y = date.getFullYear();
              var m = date.getMonth()+1;
              var d = date.getDate();
              return y+'-'+m+'-'+d;
            }
        });

  $('#nosp2d').combogrid({
           panelWidth:255,  
           idField:'no_sp2d',  
           textField:'no_sp2d',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/lpj/load_sp2d_lpj_tu',
           //url:'<?php echo base_url(); ?>index.php/rka/load_trskpd/'+kode,             
           columns:[[  
               {field:'no_sp2d',title:'NO SP2D',width:150},
               {field:'tgl_cair',title:'Tgl Terbit',width:100}  
           ]],
        });
      $('#sp2d').combogrid({
           panelWidth:255,  
           idField:'no_sp2d',  
           textField:'no_sp2d',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/lpj/load_sp2d_lpj_tu',
           //url:'<?php echo base_url(); ?>index.php/rka/load_trskpd/'+kode,             
           columns:[[  
               {field:'no_sp2d',title:'NO SP2D',width:150},
               {field:'tgl_cair',title:'Tgl Terbit',width:100}  
           ]],  
           onSelect:function(rowIndex,rowData){
         $("#tgl_sp2d").attr("value",rowData.tgl_cair);
         //detail_trans_kosong();
         load_data();
           }
        });
    
    $('#ttd1').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/BK',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_ttd1").attr("value",rowData.nama);
           } 
            });

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
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/PA',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nm_ttd2").attr("value",rowData.nama);
           } 
            });
      
    $('#cspp').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/lpj/load_lpj_tu',  
                    idField:'no_lpj',                    
                    textField:'no_lpj',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'no_lpj',title:'NO LPJ',width:60},  
                        {field:'kd_skpd',title:'SKPD',align:'left',width:60},
                        {field:'tgl_lpj',title:'Tanggal',width:60} 
                          
                    ]],
                    onSelect:function(rowIndex,rowData){
                    nomer = rowData.no_lpj;
                    kode = rowData.kd_skpd;
                    //jns = rowData.jns_spp;
                    //val_ttd(kode);
                    }   
                });
                
          $('#spp').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/lpj/load_lpj_tu',
            idField:'id',            
            rownumbers:"true", 
            fitColumns:"true",
            singleSelect:"true",
            autoRowHeight:"false",
            loadMsg:"Tunggu Sebentar....!!",
            pagination:"true",
            nowrap:"true",                
            rowStyler: function(index,row){
            if (row.status == "1"){
              return 'background-color:#03d3ff;';
              }
            },          
            columns:[[
              {field:'no_lpj',
            title:'NO LPJ',
            width:60},
                {field:'tgl_lpj',
            title:'Tanggal',
            width:40},
                {field:'nm_skpd',
            title:'Nama SKPD',
            width:170,
                align:"left"},
                {field:'ket',
            title:'Keterangan',
            width:110,
                align:"left"}
            ]],
            onSelect:function(rowIndex,rowData){
              nomer     = rowData.no_lpj;         
              kode      = rowData.kd_skpd;
              tgllpj  = rowData.tgl_lpj;
              cket    = rowData.ket;
              status_lpj= rowData.status;
              tgl_sp2d  = rowData.tgl_sp2d;
              sp2d    = rowData.sp2d;

                    
              get(nomer,kode,tgllpj,cket,status_lpj,tgl_sp2d,sp2d);
              detail_trans_3();
              load_sum_lpj(); 
              lcstatus = 'edit';                                       
            },
            onDblClickRow:function(rowIndex,rowData){
           nomer     = rowData.no_lpj;         
              kode      = rowData.kd_skpd;
              tgllpj  = rowData.tgl_lpj;
              cket    = rowData.ket;
              status_lpj= rowData.status;
              tgl_sp2d  = rowData.tgl_sp2d;
              sp2d    = rowData.sp2d;

                    
              get(nomer,kode,tgllpj,cket,status_lpj,tgl_sp2d,sp2d);
              detail_trans_3();
              load_sum_lpj(); 
              lcstatus = 'edit'; 
                section1();
            }
        });
                
           
//==grid view edit
              var nlpj      = document.getElementById('no_lpj').value;
        
      $('#dg1').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/lpj/select_data1_lpj_ag',
         queryParams:({ lpj:nlpj }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"false",
                 columns:[[
                    {field:'idx',title:'idx',width:100,align:'left',hidden:'true'},               
                    {field:'no_bukti',title:'No Bukti',width:70,align:'left'},                                          
                    {field:'kdkegiatan',title:'Kegiatan',width:180,align:'left'},
          {field:'kdrek5',title:'Rekening',width:70,align:'left'},
          {field:'nmrek5',title:'Nama Rekening',width:280},
                    {field:'nilai1',title:'Nilai',width:140,align:'right'},
                    {field:'kd_bp_skpd',title:'SKPD',width:50,align:'center'},
                    {field:'hapus',title:'',width:35,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
        ]]  
            }); 
      
    });
        
           
        
    function val_ttd(dns){
           $(function(){
            $('#ttd').combogrid({  
                panelWidth:500,  
                url: '<?php echo base_url(); ?>/index.php/tukd/pilih_ttd/'+dns,  
                    idField:'nip',                    
                    textField:'nama',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'nip',title:'NIP',width:60},  
                        {field:'nama',title:'NAMA',align:'left',width:100}
                            ]],
                    onSelect:function(rowIndex,rowData){
                    nip = rowData.nip;
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
                        $("#dn").attr("value",data.kd_skpd);
                        $("#nmskpd").attr("value",data.nm_skpd);
                                            kode   = data.kd_skpd;
                                            validate_spd(kode);
                        }                                     
          });  
        }         
    

    
    
       function get(nomer,kode,tgllpj,cket,status_lpj,tgl_sp2d,sp2d){
        $("#no_lpj").attr("value",nomer);
        $("#no_simpan").attr("value",nomer);
        $("#dn").attr("Value",kode);    
    $("#dd").datebox("setValue",tgllpj);
    $("#tgl_sp2d").attr("Value",tgl_sp2d);  
    $("#sp2d").combogrid("setValue",sp2d);
    $("#keterangan").attr("Value",cket);
    /*
    if ((status_lpj == undefined) || (status =='')|| (status =='null') ){
      status_lpj='0';
    }else{    
      status_lpj='1';   
    }
    
      //  alert(status);
      */
        tombol(status_lpj);           
        }
  
                                 
   //     function get(no_lpj,kd_skpd,tgl_lpj){
//        $("#no_lpj").attr("value",no_spj);
//        $("#dn").attr("Value",kd_skpd);   
//    $("#dd").datebox("setValue",tgl_lpj);    
//        tombol(status);           
//        }
        
    
        function kosong(){
        $("#no_lpj").attr("value",'');
        $("#no_simpan").attr("value",'');
        $("#tgl_sp2d").attr("value",'');
        $("#dd").datebox("setValue",'');
        $("#sp2d").combogrid("setValue",'');
      $("#keterangan").attr("value",'');
    $("#no_lpj").focus();
    $("#rektotal").attr("value",0)
        
        $('#save').show();
    

        st_12 = 'baru';
        detail_trans_kosong();


        lcstatus = 'tambah'
        }

    
    function getRowIndex(target){  
      var tr = $(target).closest('tr.datagrid-row');  
      return parseInt(tr.attr('datagrid-row-index'));  
    } 
       
    
    function cetak(){
        var nom=document.getElementById("no_simpan").value;
    var no_sp2d=$('#sp2d').combogrid('getValue'); 
    if (nom==''){
      alert('Simpan LPJ terlebih dahulu');
      exit();
    }
        $("#dialog-modal").dialog('open');
        $("#cspp").combogrid("setValue",nom);
    $("#nosp2d").combogrid("setValue",no_sp2d);

    } 
    
    
    function keluar(){
        $("#dialog-modal").dialog('close');
    } 
    
    
    function keluar_no(){
        $("#dialog-modal-tr").dialog('close');
    }
      
    
    function cari(){
     var kriteria = document.getElementById("txtcari").value; 
        $(function(){ 
            $('#spp').edatagrid({
         url: '<?php echo base_url(); ?>/index.php/lpj/load_lpj_tu',
         queryParams:({cari:kriteria})
        });        
     });
    }
    
     
    function section1(){
       var lcstatus = "tambah";
         $(document).ready(function(){    
             $('#section1').click();
         });
     }
     
    
    function section4(){
         $(document).ready(function(){    
             $('#section4').click();                                               
         });
     }
     
     
     function section5(){
         $(document).ready(function(){    
             $("#dialog-modal-tr").click();                                               
         });
     }
     
    function tambah_no(){
        judul = 'Input Data No Transaksi';
        $("#dialog-modal-tr").dialog({ title: judul });
        $("#dialog-modal-tr").dialog('open');
        
        document.getElementById("no_spp").focus();
        
        if ( st_12 == 'baru' ){
        $("#no1").attr("value",'');
        $("#no2").attr("value",'');
        $("#no3").attr("value",'');
        $("#no4").attr("value",'');
        $("#no5").attr("value",'');
        }
     }
     
     function tambah_no2(){
        judul = 'Input Data No Transaksi';
        $("#dialog-modal-tr").dialog({ title: judul });
        $("#dialog-modal-tr").dialog('open');
        document.getElementById("no_spp").focus();
        
        if ( st_12 == 'baru' ){
        $("#no1").attr("value",'');
        $("#no2").attr("value",'');
        $("#no3").attr("value",'');
        $("#no4").attr("value",'');
        $("#no5").attr("value",'');
        }
     } 
     
     
     function simpan(){        
        var nlpj      = document.getElementById('no_lpj').value;
        var no_simpan = document.getElementById('no_simpan').value;
      var b         = $('#dd').datebox('getValue'); 
      var sp2d      = $('#sp2d').combogrid('getValue'); 
      var tgl_sp2d  = document.getElementById('tgl_sp2d').value;
      var nket      = document.getElementById('keterangan').value;
    var d1      = (b.split("-").join("/"));
    var d2      = (tgl_sp2d.split("-").join("/"));
    var d1      = new Date(d1);
    var d2      = new Date(d2);
    var timeDiff  = (d1.getTime() - d2.getTime());
    var diffDays  = (timeDiff / (1000 * 3600 * 24));
    var tahun_input = b.substring(0, 4);
    if (tahun_input != tahun_anggaran){
      alert('Tahun tidak sama dengan tahun Anggaran');
      exit();
    }
    
    var cskpd=document.getElementById("dn").value;
    
    
    if (diffDays<0){
      alert("Tanggal LPJ Harus Lebih besar dari tanggal Terbit SP2D");
      exit();
    }
    if (nlpj == ''){
      alert("No LPJ harus terisi");
      exit();
    }
    
    

    if ( lcstatus == 'tambah' ) {
      $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:nlpj,tabel:'trhlpj',field:'no_lpj'}),
                    url: '<?php echo base_url(); ?>/index.php/lpj/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
            if(status_cek==1){
            alert("Nomor Telah Dipakai!");
            document.getElementById("nomor").focus();
            exit();
            } 
            if(status_cek==0){
            alert("Nomor Bisa dipakai");
      
      //-----
      
      $(document).ready(function(){
      $.ajax({
        type: "POST",       
        dataType : 'json',         
         url      : "<?php  echo base_url(); ?>index.php/lpj/simpan_hlpj_tu",
        data     : ({nlpj:nlpj,tgllpj:b,ket:nket,tgl_sp2d:tgl_sp2d,sp2d:sp2d}),
        beforeSend:function(xhr){
                $("#loading").dialog('open');
          },
        success:function(data){
        status = data;
        if (status == '0'){
           $("#loading").dialog('close');
           alert('Gagal Simpan...!!');
           exit();
        } else if (status !='0'){ 
        
            $('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections'); 
        for(var i=0;i<rows.length;i++){            
            cidx      = rows[i].idx;
            cnobukti1 = rows[i].no_bukti;
            ckdskpd1 = rows[i].kd_bp_skpd;
            ckdgiat   = rows[i].kdkegiatan;
            cnmgiat   = rows[i].nmkegiatan;
            ckdrek    = rows[i].kdrek5;
            cnmrek    = rows[i].nmrek5;
            cnilai    = angka(rows[i].nilai1);
            cgiat     = ckdgiat;
            no        = i + 1 ; 
            if (i>0) {
              csql = csql+","+"('"+nlpj+"','"+cnobukti1+"','"+b+"','"+ckdgiat+"','"+nket+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kode+"','"+ckdskpd1+"')";
            } else {
              csql = "values('"+nlpj+"','"+cnobukti1+"','"+b+"','"+ckdgiat+"','"+nket+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kode+"','"+ckdskpd1+"')";                                            
              }                                             
            }                       
            $(document).ready(function(){
              //alert(csql);
              //exit();
              $.ajax({
                type: "POST",   
                dataType : 'json',                 
                data: ({nlpj:nlpj,sql:csql}),
                url: '<?php echo base_url(); ?>/index.php/lpj/simpan_lpj',
                success:function(data){                        
                  status = data.pesan;   
                   if (status=='1'){
                    $("#loading").dialog('close');
                    alert('Data Berhasil Tersimpan...!!!');
                    $("#no_simpan").attr("value",nlpj);
                    lcstatus='edit';
                    //$("#no_simpan").attr("value",cnokas);
                  } else{ 
                    $("#loading").dialog('close');
                    lcstatus='tambah';
                    alert('Detail Gagal Tersimpan...!!!');
                  }                                             
                }
              });
              });            
            }
    //Akhir
        }
      });
    });
    //----
     }
    }
    });
    });
    
    }
    //else
     else{
      $(document).ready(function(){
               // alert(csql);
                $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:nlpj,tabel:'trhlpj',field:'no_lpj'}),
                    url: '<?php echo base_url(); ?>/index.php/lpj/cek_simpan',
                    success:function(data){                        
                        status_cek = data.pesan;
            if(status_cek==1 && nlpj!=no_simpan){
            alert("Nomor Telah Dipakai!");
            exit();
            } 
            if(status_cek==0 || nlpj==no_simpan){
            alert("Nomor Bisa dipakai");
    //---------     
      $(document).ready(function(){
      $.ajax({
        type: "POST",       
        dataType : 'json',         
        url      : "<?php  echo base_url(); ?>index.php/lpj/update_hlpj_tu",
        data     : ({nlpj:nlpj,tgllpj:b,ket:nket,tgl_sp2d:tgl_sp2d,sp2d:sp2d,no_simpan:no_simpan}),
        beforeSend:function(xhr){
                $("#loading").dialog('open');
          },
        success:function(data){
        status = data;
        if (status=='0'){
           $("#loading").dialog('close');
           alert('Gagal Simpan...!!');
           exit();
        } else if (status !='0'){ 
        
            $('#dg1').datagrid('selectAll');
        var rows = $('#dg1').datagrid('getSelections'); 
        for(var i=0;i<rows.length;i++){            
            cidx      = rows[i].idx;
            cnobukti1 = rows[i].no_bukti;
            ckdskpd1 = rows[i].kd_bp_skpd;
            ckdgiat   = rows[i].kdkegiatan;
            cnmgiat   = rows[i].nmkegiatan;
            ckdrek    = rows[i].kdrek5;
            cnmrek    = rows[i].nmrek5;
            cnilai    = angka(rows[i].nilai1);
            cgiat     = ckdgiat;
            no        = i + 1 ; 
            if (i>0) {
              csql = csql+","+"('"+nlpj+"','"+cnobukti1+"','"+b+"','"+ckdgiat+"','"+nket+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kode+"','"+ckdskpd1+"')";
            } else {
              csql = "values('"+nlpj+"','"+cnobukti1+"','"+b+"','"+ckdgiat+"','"+nket+"','"+ckdrek+"','"+cnmrek+"','"+cnilai+"','"+kode+"','"+ckdskpd1+"')";                                            
              }                                             
            }                       
            $(document).ready(function(){
              //alert(csql);
              //exit();
              $.ajax({
                type: "POST",   
                dataType : 'json',                 
                data: ({nlpj:nlpj,sql:csql,no_simpan:no_simpan}),
                url: '<?php echo base_url(); ?>/index.php/lpj/simpan_lpj_update',
                success:function(data){                        
                  status = data.pesan;   
                   if (status=='1'){
                    $("#loading").dialog('close');
                    alert('Data Berhasil Tersimpan...!!!');
                    $("#no_simpan").attr("value",nlpj);
                    lcstatus='edit';
                    //$("#no_simpan").attr("value",cnokas);
                  } else{ 
                    $("#loading").dialog('close');
                    lcstatus='tambah';
                    alert('Detail Gagal Tersimpan...!!!');
                  }                                             
                }
              });
              });            
            }
    //Akhir
        }
      });
    });
    //-----
    }
      }
      });
    });
      
    }
   }
    

    
     function load_sum_lpj(){          
        
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_lpj",
            data:({lpj:nomer}),
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){

                    $("#rektotal").attr('value',number_format(n['cjumlah'],2,'.',','));
                  //  $("#rektotal1").attr('value',number_format(n['rektotal'],2,'.',','));
                });
            }
         });
        });
    }
    
    
    function load_sum_tran(){                
        $(function(){      
         $.ajax({
            type: 'POST',
            data:({no_bukti:no_bukti}),
            url:"<?php echo base_url(); ?>index.php/tukd/load_sum_tran",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    //$("#rektotal").attr("value",n['rektotal']);
                    //$("#rektotal1").attr("value",n['rektotal1']);
                    $("#rektotal").attr('value',number_format(n['rektotal'],2,'.',','));
                    $("#rektotal1").attr('value',number_format(n['rektotal'],2,'.',','));

                });
            }
         });
        });
    }
   
   
    function tombol(status_lpj){
  
    if (status_lpj=='1') {
        $('#save').hide();
        $('#del').hide();
        $('#sav').hide();
        $('#dele').hide();   
        document.getElementById("p1").innerHTML="Sudah di Buat SPP TU...!!!";
     } else  if (status_lpj=='2') {
        $('#save').hide();
        $('#del').hide();
        $('#sav').hide();
        $('#dele').hide();   
        document.getElementById("p1").innerHTML="Sudah di Setujui Perben";
     } else {
         $('#save').show();
         $('#del').show();
         $('#sav').show();
         $('#dele').show();
         document.getElementById("p1").innerHTML="";
     }
    } 
    
        
    function openWindow(url)
    {
        var vnospp  =  $("#cspp").combogrid("getValue");
         
            lc  =  "?nomerspp="+vnospp+"&kdskpd="+kode+"&jnsspp="+jns ;
        window.open(url+lc,'_blank');
        window.focus();
    }
    
        
    function detail_trans_3(){
        
        $(function(){
      $('#dg1').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/lpj/select_data1_lpj_ag',
                queryParams:({ lpj:nomer }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",



                 onLoadSuccess:function(data){ 
                      
                 },
         

         
                onSelect:function(rowIndex,rowData){
                kd  = rowIndex ;  
                idx =  rowData.idx ;                                           
                },



                 columns:[[
                     {field:'idx',
           title:'idx',
           width:100,
           align:'left',
                     hidden:'true'
           },               
                     {field:'no_bukti',
           title:'No Bukti',
           width:70,
           align:'left'
           },                                          
                     {field:'kdkegiatan',
           title:'Kegiatan',
           width:180,
           align:'left'
           },
          {field:'kdrek5',
           title:'Rekening',
           width:70,
           align:'left'
           },
          {field:'nmrek5',
           title:'Nama Rekening',
           width:280
           },
                    {field:'nilai1',
           title:'Nilai',
           width:140,
                     align:'right'
                     },
                    {field:'hapus',title:'',width:35,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                    }
                    }
        ]]  
      });
    });
        }
        

        function detail_trans_kosong(){
        var no_kos = '' ;
        $(function(){
      $('#dg1').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/lpj/select_data1_lpj',
                queryParams:({ lpj:no_kos }),
                 idField:'idx',
                 toolbar:"#toolbar",              
                 rownumbers:"true", 
                 fitColumns:false,
                 autoRowHeight:"false",
                 singleSelect:"true",
                 nowrap:"true",
                 columns:[[
                     {field:'idx',
           title:'idx',
           width:100,
           align:'left',
                     hidden:'true'
           },               
                     {field:'no_bukti',
           title:'No Bukti',
           width:100,
           align:'left'
           },                                          
                     {field:'kdkegiatan',
           title:'Kegiatan',
           width:150,
           align:'left'
           },
          {field:'kdrek5',
           title:'Rekening',
           width:70,
           align:'left'
           },
          {field:'nmrek5',
           title:'Nama Rekening',
           width:280
           },
                    {field:'nilai1',
           title:'Nilai',
           width:140,
                     align:'right'
                     },
                    {field:'hapus',title:'',width:35,align:"center",
                    formatter:function(value,rec){ 
                    return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail_grid();" />';
                    }
                    }
        ]]  
      });
    });
        }
    
   
    
    function hapus_detail(){
        
        var a          = document.getElementById('no_lpj').value;
        var rows       = $('#dg1').edatagrid('getSelected');
        var ctotal_lpj = document.getElementById('rektotal').value;
        
        bbukti      = rows.no_bukti;
        bkdrek      = rows.kdrek5;
        bkdkegiatan = rows.kdkegiatan;
        bnilai      = rows.nilai1;
        ctotal_lpj  = angka(ctotal_lpj) - angka(bnilai) ;
        
        var idx = $('#dg1').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, No Bukti :  '+bbukti+'  Rekening :  '+bkdrek+'  Nilai :  '+bnilai+' ?');
        
        if ( tny == true ) {
            
            $('#rektotal').attr('value',number_format(ctotal_lpj,2,'.',','));
            $('#dg1').datagrid('deleteRow',idx);     
            $('#dg1').datagrid('unselectAll');
              
             var urll = '<?php echo base_url(); ?>index.php/tukd/dsimpan_lpj';
             $(document).ready(function(){
             $.post(urll,({cnolpj:a,cnobukti:bbukti}),function(data){
             status = data;
                if (status=='0'){
                    alert('Gagal Hapus..!!');
                    exit();
                } else {
                    alert('Data Telah Terhapus..!!');
                    exit();
                }
             });
             });    
        }     
    }
    
  function hhapus(){        
            var lpj = document.getElementById("no_lpj").value;     
            var urll= '<?php echo base_url(); ?>/index.php/lpj/hhapuslpj';                       
          if (spp !=''){
        var del=confirm('Anda yakin akan menghapus LPJ '+lpj+'  ?');
        if  (del==true){
          $(document).ready(function(){
                    $.post(urll,({no:lpj}),function(data){
                    status = data;                       
                    });
                    alert("Berhasil di Hapus!");
                    section4();
                    });       
        }
        } 
  }
  
    function hapus_detail_grid(){
        
        var a          = document.getElementById('no_lpj').value;
        var rows       = $('#dg1').edatagrid('getSelected');
        var ctotal_lpj = document.getElementById('rektotal').value;
        
        bbukti      = rows.no_bukti;
        bkdrek      = rows.kdrek5;
        bkdkegiatan = rows.kdkegiatan;
        bnilai      = rows.nilai1;
        ctotal_lpj  = angka(ctotal_lpj) - angka(bnilai) ;
        
        var idx = $('#dg1').edatagrid('getRowIndex',rows);
        var tny = confirm('Yakin Ingin Menghapus Data, No Bukti :  '+bbukti+'  Rekening :  '+bkdrek+'  Nilai :  '+bnilai+' ?');
        
        if ( tny == true ) {
            
            $('#rektotal').attr('value',number_format(ctotal_lpj,2,'.',','));
            $('#dg1').datagrid('deleteRow',idx);     
            $('#dg1').datagrid('unselectAll');
              

        }     
    }

  
  
    
    function load_data() {
    $('#dg1').datagrid('loadData', []);
        var no_sp2d      = $('#sp2d').combogrid('getValue') ;
        var ntotal_trans = 0;
        
        $(document).ready(function(){
            
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/lpj/load_data_transaksi_lpj_tu',
                data: ({no_sp2d:no_sp2d}),
                dataType:"json",
                success:function(data){                                          
                    $.each(data,function(i,n){                                    
                    xnobukti = n['no_bukti'];
                    xkdskpd  = n['kd_bp_skpd'];                                                                                        
                    xgiat    = n['kdkegiatan']; 
                    xkdrek5  = n['kdrek5'];
                    xnmrek5  = n['nmrek5'];
                    xnilai   = n['nilai1'];
                    
                    ntotal_trans = ntotal_trans + angka(xnilai) ;
                    
                    $('#dg1').edatagrid('appendRow',{no_bukti:xnobukti,kdkegiatan:xgiat,kdrek5:xkdrek5,nmrek5:xnmrek5,nilai1:xnilai,idx:i,kd_bp_skpd:xkdskpd}); 
                    $('#dg1').edatagrid('unselectAll');
                    $('#rektotal').attr('value',number_format(ntotal_trans,2,'.',','));
                    });
                 }
            });
            });   
    }
  
  function cetaktu1(cetak)
        {
      var no_sp2d  = $('#nosp2d').combogrid('getValue');   
            var no_sp2d =no_sp2d.split("/").join("abcdefghij");
      var no_lpj = $('#cspp').combogrid('getValue');   
            var no_lpj = no_lpj.split("/").join("abcdefghij");        
      var skpd   = kode; 
      var ctglttd = $('#tgl_ttd').datebox('getValue');
      var ttd1   = $("#ttd1").combogrid('getValue');
      var ttd2   = $("#ttd2").combogrid('getValue'); 
      if(ctglttd==''){
      alert('Tanggal tidak boleh kosong!');
      exit();
      }
      if(ttd1==''){
        alert('Bendahara Pengeluaran tidak boleh kosong!');
        exit();
      }
      if(ttd2==''){
        alert('Pengguna Anggaran tidak boleh kosong!');
        exit();
      }
      var ttd_1 =ttd1.split(" ").join("a");
      var ttd_2 =ttd2.split(" ").join("a");
      var url    = "<?php echo site_url(); ?>/tukd/cetaklpjtu_ag";  
      window.open(url+'/'+no_sp2d+'/'+ttd_1+'/'+skpd+'/'+cetak+'/'+ctglttd+'/'+ttd_2+'/'+no_lpj+'/LPJ-TU', '_blank');
      window.focus();
        }
    
    function cetaktu2(cetak)
        {
      var no_sp2d  = $('#nosp2d').combogrid('getValue');   
            var no_sp2d =no_sp2d.split("/").join("abcdefghij");
      var no_lpj = $('#cspp').combogrid('getValue');   
            var no_lpj = no_lpj.split("/").join("abcdefghij");  
      var skpd   = kode; 
      var ctglttd = $('#tgl_ttd').datebox('getValue');
      var ttd1   = $("#ttd1").combogrid('getValue');
      var ttd2   = $("#ttd2").combogrid('getValue'); 
      if(ctglttd==''){
      alert('Tanggal tidak boleh kosong!');
      exit();
      }
      if(ttd1==''){
        alert('Bendahara Pengeluaran tidak boleh kosong!');
        exit();
      }
      if(ttd2==''){
        alert('Pengguna Anggaran tidak boleh kosong!');
        exit();
      }
      var ttd_1 =ttd1.split(" ").join("a");
      var ttd_2 =ttd2.split(" ").join("a");
      var url    = "<?php echo site_url(); ?>/tukd/cetaklpjtusptb";  
      window.open(url+'/'+no_sp2d+'/'+ttd_1+'/'+skpd+'/'+cetak+'/'+ctglttd+'/'+ttd_2+'/'+no_lpj+'/LPJ-TU', '_blank');
      window.focus();
        }
          
  function cetak_reg_ali(cetak){      
      var skpd   = kode; 
      var cetak =cetak;
        var url    = "<?php echo site_url(); ?>/tukd/cetak_reg_lpj_ali_tu"; 
        window.open(url+'/'+skpd+'/'+cetak);
        window.focus();       
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
<div id="accordion" >
<h3><a href="#" id="section4" onclick="javascript:$('#spp').edatagrid('reload')">List LPJ </a></h3>
<div>
  <p align="left">
    <marquee direction="left" scrollamount="5" align="center" style="font-size:20px; color:#FF0000;">"Setelah Membuat LPJ Di Harapkan untuk Klik Icon Cetak di Bawah  (Cek Data Antara Transaksi SPJ dan Transaksi LPJ)"</marquee>  
  </p> 
    <p align="right">    
    <a  onclick="javascript:cetak_reg_ali(2);"><button class="button-cerah"><i class="fa fa-print"></i> Cetak</button></a> 
        <a onclick="javascript:section1();kosong();"><button class="button"><i class="fa fa-tambah"> Tambah</i></button> </a>            
        <a onclick="javascript:cari();"><button class="button-abu"> <i class="fa fa-cari"> Cari</i> </button></a>
        <input type="text" class="input" style="display: inline;" value="" id="txtcari"/>
        <table id="spp" title="List LPJ" style="width:870px;height:450px;" >  
        </table>
    </p> 
</div>

<h3><a href="#" id="section1">Input LPJ</a></h3>

   <div  style="height: 350px;">
   <p id="p1" style="font-size: x-large;color: red;"></p>
   <p> 

    <table border='0' style="font-size:11px" >

      <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
       <td  width='20%'>No LPJ</td>
       <td width='80%'>
        <input type="text" class="input" name="no_lpj" id="no_lpj" style="width:225px" onkeypress="javascript:enter(event.keyCode,'sp2d');"/> 
        <input type="text" name="no_simpan" id="no_simpan" style="width:225px" disabled hidden />
      </td>
    </tr>
    <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"> 
     <td  width='20%'>Tanggal</td>
     <td><input class="input" id="dd" name="dd" type="text" style="width:225px; display: inline; " onkeypress="javascript:enter(event.keyCode,'tgl_sp2d');"/>
     </td>   
   </tr>
   <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
     <td  width='20%'>No. SP2D</td>
     <td width='80%'><input type="text" name="sp2d" id="sp2d"  style="width:225px; display: inline;" onkeypress="javascript:enter(event.keyCode,'dd');"/>
      <input id="tgl_sp2d" name="tgl_sp2d" type="text" style="width:95px;border:0" onkeypress="javascript:enter(event.keyCode,'keterangan');"/>
    </td>
  </tr>
  <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">

   <td width='20%'>SKPD</td>
   <td width="80%">     
    <input id="dn" class="input" name="dn"  readonly="true" style="width:225px; border: 0; " />       
  </td> 






  <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">

   <td width='20%'  style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"></td>
   <td width='31%' style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
      <input name="nmskpd" id="nmskpd" class="input" style="width: 100%"></td>


   <td width='20%'  style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"></td>
   <td width='31%' style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"></td>



 </tr>


 <tr>


  <td width='20%'  style="border-right-style:hidden;border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">KETERANGAN</td>
  <td width='31%' style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;"><textarea class="textarea" name="keterangan" id="keterangan" cols="30" rows="2" ></textarea></td>
  
</tr>



<tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">

 <td width='20%'></td>
 <td width="80%">&nbsp;     

 </td> 



 <tr style="height: 30px;">



  <td colspan="4">
    <div align="right">

      <a id="save"  onclick="javascript:simpan();"><button class="button-biru"><i class="fa fa-simpan"></i> Simpan </button></a>
      <a id="del"  onclick="javascript:hhapus();javascript:section4();"><button class="button-merah"> <i class="fa fa-hapus"></i> Hapus</button></a>
      <a  onclick="javascript:cetak();"><button class="button-cerah"><i class="fa fa-print"></i> Cetak</button></a>
      <a  onclick="javascript:section4();"><button class="button-cerah"><i class="fa fa-kiri"></i> Kembali</button></a>
    </div>
  </td>                
</tr>


</table>
   
        <table id="dg1" title="Input Detail LPJ" style="width:870px;height:300%;" >  
        </table>
        
         
        <table border='0' style="width:100%;height:5%;"> 
             <td width='34%'></td>
             <td width='35%'><input class="right" type="hidden" name="rektotal1" id="rektotal1"  style="width:140px" align="right" readonly="true" ></td>
             <td width='6%'><B>Total</B></td>
             <td width='25%'><input class="right" type="text" name="rektotal" id="rektotal"  style="width:140px" align="right" readonly="true" ></td>
        </table>

   </p>

 
</div>
</div>
</div> 
      <div id="loading" title="Loading...">
      <table align="center">
      <tr align="center"><td><img id="search1" height="50px" width="50px" src="<?php echo base_url();?>/image/loadingBig.gif"  /></td></tr>
      <tr><td>Loading...</td></tr>
      </table>
      </div>
<div id="dialog-modal" title="CETAK LPJ TU">
    
    <p class="validateTips">SILAHKAN PILIH SPP</p>  
    <fieldset>
        <table>
            <tr>            
                <td width="110px" >NO SPP:</td>
                <td><input id="cspp" name="cspp" style="width: 210px;" disabled /></td>
            </tr>
            
            <tr >
      <td width="20%" height="40" >SP2D</td>
      <td width="80%"><input id="nosp2d" name="nosp2d" type="text"  style="width:155px" /></td>
    </tr>
    <tr >
      <td width="20%" height="40" >TANGGAL TTD</td>
      <td width="80%"><input id="tgl_ttd" name="tgl_ttd" style="width: 150px;" /></td>
    </tr>
    <tr>
    <td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
              <td width="20%">Bendahara Pengeluaran</td>
                            <td><input type="text" id="ttd1" style="width: 200px;" /> &nbsp;&nbsp;
              <input type="text" id="nm_ttd1" readonly="true" style="width: 200px;border:0" /> 
              
                            </td> 
                        </table>
                </div>
        </td> 
    </tr>
    <tr>
    <td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
              <td width="20%">Pengguna Anggaran</td>
                            <td><input type="text" id="ttd2" style="width: 200px;" /> &nbsp;&nbsp;
              <input type="nm_ttd2" id="nm_ttd2" readonly="true" style="width: 200px;border:0" /> 
              
                            </td> 
                        </table>
                </div>
        </td> 
    </tr>
        </table>  
    </fieldset>
    
    <div>
    </div>     

  <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetaktu1(0);">Cetak LPJ TU </a>
  <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetaktu2(0);">Cetak SPTB </a>
  <br/>
  
  <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetaktu1(1);">Cetak LPJ TU </a>      
  <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetaktu2(1);">Cetak SPTB </a>      
    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a>  
</div>
  
<div id="dialog-modal-tr" title="">
    <p class="validateTips">Pilih Nomor Transaksi</p> 
    <fieldset>
    <table align="center" style="width:100%;" border="0">
            
            <tr>
                <td>1. No Transaksi</td>
                <td></td>
                <td><input id="no1" name="no1" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>2. No Transaksi</td>
                <td></td>
                <td><input id="no2" name="no2" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>3. No Transaksi</td>
                <td></td>
                <td><input id="no3" name="no3" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>4. No Transaksi</td>
                <td></td>
                <td><input id="no4" name="no4" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>5. No Transaksi</td>
                <td></td>
                <td><input id="no5" name="no5" style="width: 320px;" />  </td>                            
            </tr>
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                            
            </tr>
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>                            
            </tr>
            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:detail_trans_2();">Pilih</a>
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_no();">Kembali</a>
                </td>                
            </tr>
        
    </table>       
    </fieldset>
</div>
</body>
</html>