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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.maskedinput.js"></script>
    
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
            height: 500,
            width: 600,
            modal: true,
            autoOpen:false
        });
        $("#npwp").mask("99.999.999.9-999.999");  //00.033.464.9-701.000
        });    
     
     $(function(){  
        
     $('#kode_u').combogrid({  
       panelWidth:500,  
       idField:'kd_urusan',  
       textField:'kd_urusan',  
       mode:'remote',
       url:'<?php echo base_url(); ?>index.php/master/ambil_burusan',  
       columns:[[  
           {field:'kd_urusan',title:'Kode Urusan',width:100},  
           {field:'nm_urusan',title:'Nama Urusan',width:400}    
       ]],  
       onSelect:function(rowIndex,rowData){
            kd_urus = rowData.kd_urusan;
            $("#nm_u").attr("value",rowData.nm_urusan.toUpperCase());
            //muncul();                
       }  
     });     
        
        
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/master/load_skpd',
        idField:'id',            
        rownumbers:"true", 
        fitColumns:"true",
        singleSelect:"true",
        autoRowHeight:"false",
        loadMsg:"Tunggu Sebentar....!!",
        pagination:"true",
        nowrap:"true",                       
        columns:[[
            {field:'kd_skpd',
            title:'Kode SKPD',
            width:10,
            align:"center"},
            {field:'kd_urusan',
            title:'Kode urusan',
            width:5,
            align:"center"},
            {field:'nm_skpd',
            title:'Nama SKPD',
            width:70},
            {field:'nilai_kua',
            title:'Nilai KUA',
            width:15,
            align:'right'}
        ]],
        onSelect:function(rowIndex,rowData){
          kd_s = rowData.kd_skpd;
          kd_u = rowData.kd_urusan;
          nm_s = rowData.nm_skpd;
          npwp = rowData.npwp;
          email = rowData.email;
          rek = rowData.rekening;
          rek_pend = rowData.rekening_pend;
          alamat = rowData.alamat;
          kdpos = rowData.kodepos;
          bank = rowData.bank;
          kua = rowData.nilai_kua;
          obskpd = rowData.obskpd;
          get(kd_s,kd_u,nm_s,npwp,rek,alamat,kdpos,bank,kua,obskpd,rek_pend,email); 
          lcidx = rowIndex;  
 
                                       
        },
        onDblClickRow:function(rowIndex,rowData){
           lcidx = rowIndex;
           judul = 'Edit Data Urusan'; 
           edit_data();   
        }
        
        });

        $('#kd_bank').combogrid({  
           panelWidth:700,  
           idField:'kode',  
           textField:'kode',  
           mode:'remote',
           url:'<?php echo base_url(); ?>index.php/master/load_bank',
//         queryParams:({kode:kode}),
           columns:[[  
               {field:'kode',title:'Kode',width:150},  
               {field:'nama',title:'Nama',width:500}    
           ]],  onSelect:function(rowIndex,rowData){
                    $("#nm_bank").attr("value",rowData.nama);
                    }   
           });
        
    });        


    function get(kd_s,kd_u,nm_s,npwp,rek,alamat,kdpos,bank,kua,obskpd,rek_pend,email) {
        
        $("#kode").attr("value",kd_s);
        $("#kode_u").combogrid("setValue",kd_u);
        $("#kd_bank").combogrid("setValue",bank);
        $("#nama").attr("value",nm_s);
        $("#npwp").attr("value",npwp); 
        $("#reke").attr("value",rek);     
        $("#reke_pend").attr("value",rek_pend);     
        $("#alamat").attr("value",alamat);     
        $("#kdpos").attr("value",kdpos);
        $("#email").attr("value",email);     
        $("#n_kua").attr("Value",kua);      
        $("#obnmskpd").attr("value",obskpd);
    }


    function kosong(){
        $("#kode").attr("value",'');
        $("#kode_u").combogrid("setValue",'');
        $("#kd_bank").combogrid("setValue",'');
        $("#obnmskpd").attr("value",'');
        $("#nama").attr("value",'');
        $("#npwp").attr("value",'');
        $("#email").attr("value",'');
        $("#reke").attr("value",''); 
        $("#reke_pend").attr("value",'');
        $("#alamat").attr("value",'');     
        $("#kdpos").attr("value",'');
        $("#n_kua").attr("value",0); 
    }
    
    function muncul(){
        //alert(kd_s);
        var c_urus=kd_urus+'.';
        var c_skpd=kd_s;
        if(lcstatus=='tambah'){ 
            $("#kode").attr("value",c_urus);
        } else {
            $("#kode").attr("value",c_skpd);
        }     
    }
    
    function cari(){
    var kriteria = document.getElementById("txtcari").value; 
    $(function(){ 
     $('#dg').edatagrid({
        url: '<?php echo base_url(); ?>/index.php/master/load_skpd',
        queryParams:({cari:kriteria})
        });        
     });
    }
    
       function simpan_skpd(){
        var ckode = document.getElementById('kode').value;
        var cobskpd = document.getElementById('obnmskpd').value;
        var ckode_u= $('#kode_u').combogrid('getValue');
        var cbank= $('#kd_bank').combogrid('getValue');
        var cnama = document.getElementById('nama').value;
        var cnpwp = document.getElementById('npwp').value;
        var cemail = document.getElementById('email').value;
        var crek = document.getElementById('reke').value;
        var crek_pend = document.getElementById('reke_pend').value;
        var alamat = document.getElementById('alamat').value;
        var kdpos = document.getElementById('kdpos').value;
        var corg = ckode.substr(0,7);
        var cnpwp1 = cnpwp.split(".").join("");
        var cnpwp1 = cnpwp1.split("-").join("");
        var lcnpwp = cnpwp1.length; 
        
        if(lcnpwp!=15){
            alert('NPWP tidak lengkap cek lagi');
            return;
        }
        var vn_kua = document.getElementById('n_kua').value;
        vn_kua=angka(vn_kua);
                
        if (ckode==''){
            alert('Kode Golongan Tidak Boleh Kosong');
            exit();
        } 
        if (ckode_u==''){
            alert('Kode Golongan Tidak Boleh Kosong');
            exit();
        } 
        if (cnama==''){
            alert('Nama Golongan Tidak Boleh Kosong');
            exit();
        }

        
        if(lcstatus=='tambah'){ 
            lcinsert = "(kd_skpd,kd_urusan,nm_skpd,npwp,rekening,alamat,kodepos,bank,nilai_kua,kd_org,obskpd,rekening_pend,email)";
            lcvalues = "('"+ckode+"','"+ckode_u+"','"+cnama+"','"+cnpwp+"','"+crek+"','"+alamat+"','"+kdpos+"','"+cbank+"','"+vn_kua+"','"+corg+"','"+cobskpd+"','"+crek_pend+"','"+cemail+"')";

            $(document).ready(function(){
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/master/simpan_master',
                    data: ({tabel:'ms_skpd',kolom:lcinsert,nilai:lcvalues,cid:'kd_skpd',lcid:ckode}),
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
           
        } else{
            /*
            lcquery = "UPDATE ms_skpd SET nm_skpd='"+cnama+"',kd_urusan='"+ckode_u+"',npwp='"+cnpwp+"',rekening='"+crek+"',nilai_kua='"+vn_kua+"'"+
                      ",alamat='"+alamat+"',kodepos='"+kdpos+"',bank='"+cbank+"',obskpd='"+cobskpd+"',rekening_pend='"+crek_pend+"'"+  
                      " where kd_skpd='"+ckode+"' update trdrka set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"' update trskpd "+ 
                      "set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trskpd set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhspd set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhspp set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhspm set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhsp2d set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhtransout set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhtrmpot set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhstrpot set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trdrka_pend set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'";
            */
            lcquery = "UPDATE ms_skpd SET nm_skpd='"+cnama+"',kd_urusan='"+ckode_u+"',npwp='"+cnpwp+"',rekening='"+crek+"',nilai_kua='"+vn_kua+"'"+
                      ",alamat='"+alamat+"',kodepos='"+kdpos+"',bank='"+cbank+"',obskpd='"+cobskpd+"',email='"+cemail+"',rekening_pend='"+crek_pend+"'"+  
                      " where kd_skpd='"+ckode+"' update trdrka set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"' update trskpd "+ 
                      "set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trskpd set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhspd set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhspp set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhspm set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhsp2d set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhtransout set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhtrmpot set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'"+
                      "update trhstrpot set nm_skpd='"+cnama+"' where kd_skpd='"+ckode+"'";         
            
            $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>/index.php/master/update_master',
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
        judul = 'Edit Data SKPD';
        $("#dialog-modal").dialog({ title: judul });
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=true;
        }    
        
    
     function tambah(){
        lcstatus = 'tambah';
        judul = 'Input Data SKPD';
        $("#dialog-modal").dialog({ title: judul });
        kosong();
        $("#dialog-modal").dialog('open');
        document.getElementById("kode").disabled=false;
        document.getElementById("kode").focus();
        } 
     function keluar(){
        $("#dialog-modal").dialog('close');
        lcstatus = 'edit';
     }    
     

     
     function hapus(){
        var ckode = document.getElementById('kode').value;
        
           $.ajax({
                    type: "POST",   
                    dataType : 'json',                 
                    data: ({no:ckode.substr(0,4),tabel:'trskpd',field:'left(kd_kegiatan1,4)'}),
                    url: '<?php echo base_url(); ?>/index.php/rka/cek_data',
                    success:function(data){                        
                        status_cek = data.pesan;
                        if(status_cek==1 ){
                        alert("Kode SKPD Telah Dipakai dan tidak bisa di Hapus!");
                        exit();
                        }
                        
                        if(status_cek==0 ){
                            
                            var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
                            $(document).ready(function(){
                             $.post(urll,({tabel:'ms_skpd',cnid:ckode,cid:'kd_skpd'}),function(data){
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
                    }
                });
        
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
  
    
  
   </script>

</head>
<body>

<div id="content"> 
<h3 align="center"><u><b><a>INPUTAN MASTER SKPD</a></b></u></h3>
    <div align="center">
    <p align="center">     
    <table style="width:400px;" border="0">
        
        <tr>
        <td colspan="4">
        <table id="dg" title="LISTING DATA SKPD" style="width:900px;height:440px;" >  
        </table>
        </td>
        </tr>
    </table>  
    </p> 
    </div>   
</div>

<div id="dialog-modal" title="">
    <p class="validateTips">Semua Inputan Harus Di Isi.</p> 
    <fieldset>
     <table align="center" style="width:100%;" border="0">
            <tr>
                <td width="30%">KODE URUSAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode_u" style="width:50px;"/><input type="text" id="nm_u" style="width:310px;"/></td>  
            </tr> 
           <tr>
                <td width="30%">KODE SKPD</td>
                <td width="1%">:</td>
                <td><input type="text" id="kode" style="width:250px;"/></td>  
            </tr>
                       
            <tr>
                <td width="30%">NAMA SKPD</td>
                <td width="1%">:</td>
                <td><textarea name="nama" id="nama" cols="60" rows="1" readonly="true" /></textarea><!--<input type="text" id="nama" style="width:360px;"/>--></td>  
            </tr>
            <tr>
                <td width="30%">OB SKPD (CMS)</td>
                <td width="1%">:</td>
                <td><input type="text" id="obnmskpd" style="width:200px;" placeholder="Maksimal 8 Karakter" maxlength="15"/></td>  
            </tr>
            <tr>
                <td width="30%">NPWP</td>
                <td width="1%">:</td>
                <td><input type="text" id="npwp" style="width:200px;"/></td>  
            </tr>
            <tr>
                <td width="30%">BANK</td>
                <td width="1%">:</td>
                <td><input type="text" id="kd_bank" style="width:50px;"/><input type="text" id="nm_bank" style="width:310px;"/></td>  
            </tr>
            <tr>
                <td width="30%">REKENING PENERIMAAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="reke_pend" style="width:200px;"/></td>  
            </tr>
            <tr>
                <td width="30%">REKENING PENGELUARAN</td>
                <td width="1%">:</td>
                <td><input type="text" id="reke" style="width:200px;"/></td>  
            </tr>
            <tr>
                <td width="30%">EMAIL</td>
                <td width="1%">:</td>
                <td><input type="text" id="email" style="width:310px;"/></td>  
            </tr>
            <tr>
                <td width="30%">ALAMAT</td>
                <td width="1%">:</td>
                <td><input type="text" id="alamat" style="width:300px;"/></td>  
            </tr>
            <tr>
                <td width="30%">KODE POS</td>
                <td width="1%">:</td>
                <td><input type="text" id="kdpos" style="width:200px;"/></td>  
            </tr>

            <tr>
                <td width="30%">NILAI KUA</td>
                <td width="1%">:</td>
                <td><input type="text" id="n_kua" style="width:200px;" style="width: 196px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" disabled//></td>  
            </tr>
            
            <tr>
            <td colspan="3">&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3" align="center"><a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan_skpd();">Simpan</a>
                <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>                
            </tr>
        </table>       
    </fieldset>
</div>

</body>

</html>