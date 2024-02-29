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
    
    
    var kode     = '';
    var giat     = '';
    var nomor    = '';
    var judul    = '';
    var cid      = 0 ;
    var lcidx    = 0 ;
    var lcstatus = '';    
    var cekstatus= 0;
    var kode_ringkas = '';
    
    $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
            height: 480,
            width: 900,
            modal: true,
            autoOpen:false,
        });
        //get_skpd();
    get_tahun();
        });    
     
     $(function(){ 
     $('#dg').edatagrid({
        rowStyler:function(index,row){
                
        if ((row.status_upload==1 && row.status_validasi==1)){
       return 'background-color:#B0E0E6';
        }else if ((row.status_upload==1)){
       return 'background-color:#98FB98;';
        }
        
    },
    url: '<?php echo base_url(); ?>/index.php/cms/load_setorbidang_bnk',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
          {field:'no_kas',
        title:'Nomor Kas',
        width:50,
            align:"center"},
            {field:'tgl_kas',
        title:'Tanggal Kas',
        width:30,
            align:"center"},
            {field:'kd_skpd',
        title:'S K P D',
        width:30,
            align:"center"},
            {field:'nilai',
        title:'N I L A I',
        width:50,
            align:"center"}
        ]],
        onSelect:function(rowIndex,rowData){
          nomor   = rowData.no_kas;
          tgl     = rowData.tgl_kas;
          lcketcms   = rowData.ket_tujuan;
          //tgl_bukti     = rowData.tgl_bukti;
          lcspp  = rowData.jenis_spp;
          kode    = rowData.kd_skpd;
          kode_sumber    = rowData.kd_skpd_sumber;
          lnnilai = rowData.nilai;          
          //lcnmrek = rowData.nm_rekening;
          lcket   = rowData.keterangan;
          srekwal     = rowData.rekening_awal;         
          snmrek_awal    = rowData.nm_rekening_tujuan;
          sbank_tjn     = rowData.bank_tujuan;         
          srek_tjn    = rowData.rekening_tujuan;
          cekstatus = rowData.status_upload;
          lcidx   = rowIndex;          
          get(nomor,tgl,kode,lnnilai,lcket,kode_sumber,lcspp,lcketcms,srekwal,snmrek_awal,sbank_tjn,srek_tjn);             
        },
        onDblClickRow:function(rowIndex,rowData){
            nomor   = rowData.no_kas;
          tgl     = rowData.tgl_kas;
          //no_bukti   = rowData.no_bukti;
          //tgl_bukti     = rowData.tgl_bukti;
          lcspp  = rowData.jenis_spp;
          kode    = rowData.kd_skpd;
          kode_sumber    = rowData.kd_skpd_sumber;
          lnnilai = rowData.nilai;
          //lcbank  = rowData.bank;
          //lcnmrek = rowData.nm_rekening;
          lcket   = rowData.keterangan;
          lcidx   = rowIndex;          
          get(nomor,tgl,kode,lnnilai,lcket,kode_sumber,lcspp,lcketcms,lcketcms,srekwal,snmrek_awal,sbank_tjn,srek_tjn);    
           lcidx = rowIndex;
           judul = 'Edit Data Penetapan'; 
           edit_data();   
        }
        });
        
        $('#rekening_awal').combogrid({            
            url:"<?php echo base_url(); ?>index.php/cms/cari_rekening",
            panelWidth:150,
            idField:'rek_bend',
            textField:'rek_bend',
            columns:[[
                {field:'rek_bend',title:'Rekening Bendahara',width:130}
            ]]
        });
        
        $('#rekening_tujuan').combogrid({            
            url:"<?php echo base_url(); ?>index.php/cms/cari_rekening_tujuan/1",
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
            url:"<?php echo base_url(); ?>index.php/cms/cari_bank",
            panelWidth:150,
            idField:'nama',
            textField:'nama',
            columns:[[
                {field:'nama',title:'Bank',width:130}
            ]]
        });
        
        $('#tanggal').datebox({  
            required:true,
            formatter :function(date){
              var y = date.getFullYear();
              var m = date.getMonth()+1;
              var d = date.getDate();
              return y+'-'+m+'-'+d;
            },
           onSelect: function(date){
        var y = date.getFullYear();
              var m = date.getMonth()+1;
              var d = date.getDate();
        $("#tgl_bukti").datebox("setValue",y+'-'+m+'-'+d);
         }
        });
        
        $('#tglbku').datebox({  
            required:true,
            formatter :function(date){
              var y = date.getFullYear();
              var m = date.getMonth()+1;
              var d = date.getDate();
              return y+'-'+m+'-'+d;
            },
           onSelect: function(date){
        var y = date.getFullYear();
              var m = date.getMonth()+1;
              var d = date.getDate();                
         }
        });
        
        $('#tgl_bukti').datebox({  
            required:true,
            formatter :function(date){
              var y = date.getFullYear();
              var m = date.getMonth()+1;
              var d = date.getDate();
              return y+'-'+m+'-'+d;
            },
            onSelect: function(date){
          jaka1 = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
         }
        });
        
        $('#skpd').combogrid({  
           panelWidth:700,  
           idField:'kd_skpd',  
           textField:'kd_skpd',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/cms/skpd_3',  
           columns:[[  
               {field:'kd_skpd',title:'Kode SKPD',width:100},  
               {field:'nm_skpd',title:'Nama SKPD',width:700}    
           ]],  
           onSelect:function(rowIndex,rowData){
               kode = rowData.kd_skpd;
               kode_sumber = rowData.kd_skpd_sumber;       
               kode_ringkas = rowData.kd_ringkas;   
               $("#skpd_sumber").attr("value",kode_sumber);
               $("#skpd_ringkas").attr("value",kode_ringkas);              
               $("#nmskpd").attr("value",rowData.nm_skpd.toUpperCase());
           }  
        });
        
       $('#beban').combogrid({  
           panelWidth:100,  
           idField:'id',  
           textField:'jns',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/cms/load_jns_spp_drop',  
           columns:[[  
               {field:'jns',title:'Jenis Beban',width:90}    
           ]],  
           onSelect:function(rowIndex,rowData){
               xid = rowData.id;    
               
               if(xid=='1'){
                   var init = 'UP/GU'; 
               }else if(xid=='3'){
                   var init = 'TU'; 
               }else if(xid=='4' || xid=='6'){
                   var init = 'LS'; 
               }
               
               var ketcms = 'DROP.'+init+'.'+kode_ringkas;
               $("#ketcms").attr("value",ketcms);                              
           }  
        }); 
    });

    
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
     
     /*function get_skpd()
     {
          $.ajax({
            url:'<?php echo base_url(); ?>index.php/rka/config_skpd',
            type: "POST",
            dataType:"json",                         
            success:function(data){
                                    $("#skpd").combogrid("setValue",data.kd_skpd);
                        $("#nmskpd").attr("value",data.nm_skpd);
                        }                                     
     });
     }*/
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
       
    function get(nomor,tgl,kode,lnnilai,lcket,kode_sumber,lcspp,lcketcms,lcketcms,srekwal,snmrek_awal,sbank_tjn,srek_tjn){
        $("#nomor").attr("value",nomor);
        $("#tanggal").datebox("setValue",tgl);
        //$("#no_bukti").attr("value",no_bukti);
        //$("#tgl_bukti").datebox("setValue",tgl_bukti);
        $("#skpd").combogrid("setValue",kode);         
        $("#skpd_sumber").attr("value",kode_sumber);
        $("#nilai").attr("value",lnnilai);
        $("#beban").combogrid("setValue",lcspp);
        $("#rekening_awal").combogrid("setValue",srekwal);        
        $("#nm_rekening_tujuan").attr("Value",snmrek_awal);
        $("#kd_bank_tujuan").combogrid("setValue",sbank_tjn);        
        $("#rekening_tujuan").combogrid("setValue",srek_tjn);
        $("#ketcms").attr("value",lcketcms);
        $("#ket").attr("value",lcket);
        //$("#skpd").combogrid('disable');
    load_sisa();
    }
    
    function kosong(){
        $("#nomor").attr("value",'');
        $("#tanggal").datebox("setValue",'');
        $("#nilai").attr("value",'');
        $("#ketcms").attr("value",'');        
        $("#beban").combogrid("setValue",'');
        // $("#nmbank").attr("value",'');
       // $("#nmrek").attr("value",'');
        $("#ket").attr("value",'');
        $("#rekening_awal").combogrid("setValue",'');        
        $("#nm_rekening_tujuan").attr("Value",'');
        $("#kd_bank_tujuan").combogrid("setValue",'');        
        $("#rekening_tujuan").combogrid("setValue",'');
    load_sisa();
    get_nourut();
    }
    
    function load_sisa(){           
        $(function(){      
         $.ajax({
            type: 'POST',
            url:"<?php echo base_url(); ?>index.php/cms/load_sisa_bank",
            dataType:"json",
            success:function(data){ 
                $.each(data, function(i,n){
                    $("#sisa_uang").attr("value",n['sisa']);
                   // $("#rekspm1").attr("value",n['rekspm1']);
                });
            }
         });
        });
    }
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
    url: '<?php echo base_url(); ?>/index.php/cms/load_setorbidang_bnk',
        queryParams:({cari:kriteria})
        });        
     });
     }
    
    
    
     function simpan_ambilsmp(){
        
        var cno     = document.getElementById('nomor').value;
        //var cno_bukti     = document.getElementById('no_bukti').value;
        var ctgl    = $('#tanggal').datebox('getValue');
        //var ctgl_bukti    = $('#tgl_bukti').datebox('getValue');
        var cskpd   = $('#skpd').combogrid('getValue');
        var cskpd_sumber   = document.getElementById('skpd_sumber').value;
        var cskpd_ringkas  = document.getElementById('skpd_ringkas').value;
        var lnnilai = angka(document.getElementById('nilai').value);
        var sisa_bank = angka(document.getElementById('sisa_uang').value);  
        //var cbank   = $('#bank').combogrid('getValue');
        var ketcms  = document.getElementById('ketcms').value;
        var lcket   = document.getElementById('ket').value;
        var tahun_input = ctgl.substring(0, 4);
        //alert(cskpd_sumber);
        var jnsbeban = $('#beban').combogrid('getValue');
        
        if (tahun_input != tahun_anggaran){
        alert('Tahun tidak sama dengan tahun Anggaran');
        exit();
        }
        if (lnnilai>sisa_bank){
            alert('Nilai Lebih Besar dari Sisa Bank');
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
        if (jnsbeban==''){
            alert('Jenis Beban Tidak Boleh Kosong');
            exit();
        }
        
        var crekawal = $('#rekening_awal').combogrid('getValue');
        var cnmrekawal = document.getElementById('nm_rekening_tujuan').value;
        var crektujuan = $("#rekening_tujuan").combogrid("getValue"); 
        var cbanktujuan = $('#kd_bank_tujuan').combogrid('getValue');            
        
        if (crekawal == '' || cnmrekawal == '' || crektujuan == '' || cbanktujuan == ''){
        alert('Isian Rekening Belum Lengkap!');
        exit();
        }
        
        if (lcstatus=='tambah'){ 
                    
                    lcinsert = "(no_kas,tgl_kas,no_bukti,tgl_bukti,kd_skpd,nilai,keterangan,kd_skpd_sumber,jenis_spp,rekening_awal,nm_rekening_tujuan,rekening_tujuan,bank_tujuan,ket_tujuan,status_validasi,status_upload)";
                    lcvalues = "('"+cno+"','"+ctgl+"','"+cno+"','"+ctgl+"','"+cskpd+"','"+lnnilai+"','"+lcket+"','"+cskpd_sumber+"','"+jnsbeban+"','"+crekawal+"','"+cnmrekawal+"','"+crektujuan+"','"+cbanktujuan+"','"+ketcms+"','0','0')";
        
                    $(document).ready(function(){
                        $.ajax({
                            type: "POST",
                            url: '<?php echo base_url(); ?>index.php/cms/simpan_ambil_simpanan_bidang_bnk',
                            data: ({tabel:'tr_setorpelimpahan_bank_cms',kolom:lcinsert,nilai:lcvalues,cid:'no_kas',lcid:cno}),
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
                                    exit();
                                }
                            }
                        });
                    });    
                 
                  } else {
                    
                    lcquery = "UPDATE tr_setorpelimpahan_bank_cms SET no_kas='"+cno+"',tgl_kas='"+ctgl+"',no_bukti='"+cno+"',tgl_bukti='"+ctgl+"', kd_skpd='"+cskpd+"', nilai='"+lnnilai+"',ket_tujuan='"+ketcms+"', kd_skpd_sumber='"+cskpd_sumber+"', keterangan='"+lcket+"', jns_spp='"+jnsbeban+"',rekening_awal='"+crekawal+"',nm_rekening_tujuan='"+cnmrekawal+"',rekening_tujuan='"+crektujuan+"',bank_tujuan='"+cbanktujuan+"' where no_kas='"+cno+"' AND kd_skpd='"+cskpd+"'";
                   
                    $(document).ready(function(){
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/cms/update_ambilsimpanan_bnk',
                        data: ({st_query:lcquery}),
                        dataType:"json",
                        success:function(data){
                                status = data;
                                if (status=='0'){
                                    alert('Gagal Simpan..!!');
                                    exit();
                                }else{
                                    alert('Data Tersimpan..!!');
                                    exit();
                                }
                            }
                    });
                    });
                }   
        
        $("#dialog-modal").dialog('close');
        $('#dg').edatagrid('reload');
    } 
    
     
    function edit_data(){
        lcstatus = 'edit';
        judul = 'Edit Data Setor Simpanan';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("nomor").disabled=true;
        }    
        
    
    function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data Setor Perbidang';
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
        
        if(cekstatus==1){
            alert('Tidak dapat dihapus..!');
            exit();
        }else{
        
        var nomor     = document.getElementById('nomor').value;
        var kode   = $('#skpd').combogrid('getValue');    
        var urll = '<?php echo base_url(); ?>index.php/cms/hapus_ambilsimpanan_bidang_bnk';
        $(document).ready(function(){
         $.post(urll,({no:nomor,skpd:kode,tabel:'tr_setorpelimpahan_bank_cms'}),function(data){
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
        });   }
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
    
    function cetak_list(){
        
    var kriteria = $('#tglbku').datebox('getValue'); 
    if(kriteria==''){alert('Tanggal Tidak Boleh Kosong'); exit();}
    
    var url = '<?php echo site_url(); ?>/cms/cetak_listsimpanan_bank';
    window.open(url+'/'+kriteria+'/LIST DROPPING DANA SIMPANAN BANK '+kriteria, '_blank');
    window.focus();        
    }
    
    function get_nourut()
        {
          $.ajax({
            url:'<?php echo base_url(); ?>index.php/cms/no_urut',
            type: "POST",
            dataType:"json",                         
            success:function(data){
                        $("#no_bukti").attr("value",data.no_urut);
                        $("#nomor").attr("value",data.no_urut);
                        }                                     
          });  
        }
  
   </script>

</head>
<body>

<div id="content"> 
<div id="accordion">
<h3 align="center"><u><b><a href="#" id="section1">INPUTAN SETOR SIMPANAN BANK PERBIDANG - NON TUNAI (CMS)</a></b></u></h3>
    <div>
    <p align="right"> 
        Tanggal
        <input name="tglbku" type="text" id="tglbku" style="width:100px; border: 0;"/>            
        &nbsp;
        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_list();">Cetak List</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;        
        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>               
        <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
        <input type="text" value="" id="txtcari"/>
        <table id="dg" title="Listing data Setor Perbidang" style="width:870px;height:450px;" >  
        </table>
 
    </p> 
    </div>   

</div>

</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
     
            <tr>
                <td>Tujuan SKPD</td>
                <td></td>
                <td><input id="skpd" name="skpd" style="width: 140px;" />  <input type="text" id="nmskpd" style="border:0;width: 600px;" readonly="true"/></td>                            
            </tr>
            <tr>
                <td colspan="3">
                  <input type="hidden" id="skpd_sumber" name="skpd_sumber" />
                  <input type="hidden" id="skpd_ringkas" name="skpd_ringkas" />
                </td>
            </tr>
            <tr>
                <td>No. Kas</td>
                <td></td>
                <td><input type="text" id="nomor" style="width: 200px;"/></td>  
            </tr>            
            <tr>
                <td>Tgl Kas </td>
                <td></td>
                <td><input type="text" id="tanggal" style="width: 140px;" /></td>
            </tr>
            <tr>
                <td colspan="3">
                <table width="100%">
                    <tr bgcolor="#E6E6FA">
                        <td>Rek. Bank Bendahara</td>
                        <td><input type="text" id="rekening_awal" style="border:0;width: 150px;" readonly="true"/></td>
                        <td>Nama Bank Tujuan</td>
                        <td><input type="text" id="kd_bank_tujuan" style="border:0;width: 200px;" onkeyup="javascript:cek_huruf(this);"/></td>
                    </tr>
                    <tr bgcolor="#FFE4E1">
                        <td>Rek. Bank Tujuan</td>
                        <td><input type="text" id="rekening_tujuan" style="border:0;width: 150px;" /></td>
                        <td>Nama Rek. Tujuan</td>
                        <td><input type="text" id="nm_rekening_tujuan" style="border:0;width: 200px;"/></td>
                    </tr>
                    
                </table>                
                </td>
            </tr>    
             <!--<tr>
                <td>No. Bukti</td>
                <td></td>
                <td><input type="text" id="no_bukti" style="width: 200px;"/></td>  
            </tr>            
            <tr>
                <td>Tgl Bukti </td>
                <td></td>
                <td><input type="text" id="tgl_bukti" style="width: 140px;" /></td>
            </tr>-->
       <tr>
                <td>Jenis Beban</td>
                <td></td>
                <td>
                <input type="text" id="beban" style="width: 100px;" />&nbsp;<input type="text" id="ketcms" style="width: 200px; border:0;"/>
                </td>                                             
            </tr>    
       <tr>
                <td>Sisa Kas Bank</td>
                <td></td>
                <td><input type="text" id="sisa_uang" style="width: 200px; text-align: right;" /></td> 
            </tr>
      
            <tr>
                <td>Nilai</td>
                <td></td>
                <td><input type="text" id="nilai" style="width: 200px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))"/></td> 
            </tr>
            
            <!---onkeypress="return(currencyFormat(this,',','.',event))--->
            <!--<tr>
                <td>Bank</td>
                <td></td>
                <td><input type="text" id="bank" style="width: 140px;" /> <input type="text" id="nmbank" style="border:0;width: 600px;" readonly="true"/> </td>                
            </tr> 
                        
            <tr>
                <td>Nama Rekening</td>
                <td colspan="2"><textarea rows="1" cols="50" id="nmrek" style="width: 740px;"></textarea>
                </td> 
            </tr>-->           
            
            <tr>
                <td>Keterangan</td>
                <td colspan="2"><textarea rows="2" cols="50" id="ket" style="width: 740px;"></textarea>
                </td> 
            </tr>
            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_ambilsmp();">Simpan</a>
            <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>
</body>
</html>