

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
    var ctk = '1';
        
    
     $(function(){ 
            $("#accordion").accordion();
             $("#nm_skpd").attr("value",'');
             $('#ttd1').combogrid();
             $('#ttd2').combogrid();
      

        $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            }
        }); 
        
        
        $('#skpd').combogrid({  
            panelWidth:700,  
            idField:'kd_skpd',  
            textField:'kd_skpd',  
            mode:'remote',
            url:'<?php echo base_url(); ?>index.php/anggaran_murni/skpduser_induk',  
            columns:[[  
                {field:'kd_skpd',title:'Kode SKPD',width:150},  
                {field:'nm_skpd',title:'Nama SKPD',width:600}    
            ]],
            onSelect:function(rowIndex,rowData){
                skpd = rowData.kd_skpd;
                $("#nmskpd").attr("value",rowData.nm_skpd);
                cetakbawah();
                ttd(skpd);
           
                }  
            });       
    });        

function ttd(kode){
           $(function(){
            $('#ttd1').combogrid({  
            panelWidth:500,  
            url: '<?php echo base_url(); ?>/index.php/cetak_rka/load_tanda_tangan/'+kode,  
                idField:'id_ttd',                    
                textField:'nama',
                mode:'remote',  
                fitColumns:true,  
                columns:[[  
                    {field:'nip',title:'NIP',width:100},  
                    {field:'nama',title:'NAMA',align:'left',width:100},
                    {field:'jabatan',title:'JABATAN',align:'left',width:100}                              
                ]],
                onSelect:function(rowIndex,rowData){
                nip = rowData.nip;
                
                }   
            });
            
            
            $('#ttd2').combogrid({  
                panelWidth:500,  
                idField:'id_ttd',  
                textField:'nama',
                fitColumns:true,   
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/cetak_rka/load_tanda_tangan_bud/'+kode,  
                columns:[[  
                    {field:'nip',title:'NIP',width:100},  
                    {field:'nama',title:'Nama',width:100},
                    {field:'jabatan',title:'JABATAN',align:'left',width:100}    
                ]]  
            });   
                       });
}
     

    function cek2(cetak){
        if ($('input[name="chkpa"]:checked').val()=='1'){
            var  ttd        = $('#ttd1').combogrid('getValue');
        }

        if ($('input[name="chkppkd"]:checked').val()=='1'){
            var  ttd       = $('#ttd2').combogrid('getValue');
        }

        if(ttd==undefined){
            alert("Harap lengkapi isian");
        }   

        var  ctglttd    = $('#tgl_ttd').datebox('getValue');
        var  ttd2       = "00";
        var  ckdskpd    = $('#skpd').combogrid('getValue');
        var  tipe_doc   = document.getElementById('tipe_doc').value;
        var  status_anggaran1   = document.getElementById('status_anggaran1').value;
        var  status_anggaran2   = document.getElementById('status_anggaran2').value;
        var  cekbok     = document.getElementById('rinci').checked;
        var  gaji      = document.getElementById('gaji').checked;
        if(gaji==false){
            var cgaji=0;
        }else{
            var cgaji=1;
        }


        if(cekbok==false){
            var url="<?php echo site_url(); ?>/cetak_rka/preview_rka_skpd_pergeseran/"+ctglttd+'/'+ttd+'/'+ttd2+'/'+ckdskpd+'/'+cetak+'/hide/'+tipe_doc+'/'+cgaji+'/'+status_anggaran1+'/'+status_anggaran2+'/Cetak_RKA'+ckdskpd;
        }else{
            var url="<?php echo site_url(); ?>/cetak_rka/preview_rka_skpd_pergeseran/"+ctglttd+'/'+ttd+'/'+ttd2+'/'+ckdskpd+'/'+cetak+'/detail/'+tipe_doc+'/'+cgaji+'/'+status_anggaran1+'/'+status_anggaran2+'/Cetak_RKA'+ckdskpd;            
        }

        
        if (ckdskpd == ''){
            alert("Pilih Nama SKPD Terlebih Dahulu");
        } else if (ttd==''){
            alert("Pilih Penandatangan Terlebih Dahulu");
        } else {
            window.open(url);
        }
    }
    
    function cetakbawah(){
        var ckdskpd = $('#skpd').combogrid('getValue');
        var  tipe_doc = document.getElementById('tipe_doc').value;
        var cekbok  = document.getElementById('rinci').checked;
        var  status_anggaran1   = document.getElementById('status_anggaran1').value;
        var  status_anggaran2   = document.getElementById('status_anggaran2').value;
        var  gaji      = document.getElementById('gaji').checked;
        if(gaji==false){
            var cgaji=0;
        }else{
            var cgaji=1;
        }


        if(cekbok==false){
            url="<?php echo site_url(); ?>/cetak_rka/preview_rka_skpd_pergeseran/2020-1-1/tanpa/tanpa/"+ckdskpd+"/0/hide/"+tipe_doc+"/"+cgaji+"/"+status_anggaran1+"/"+status_anggaran2+"/aa"; 
        }else{
            url="<?php echo site_url(); ?>/cetak_rka/preview_rka_skpd_pergeseran/2020-1-1/tanpa/tanpa/"+ckdskpd+"/0/detail/"+tipe_doc+"/"+cgaji+"/"+status_anggaran1+"/"+status_anggaran2+"/aa";
        }
        if(ckdskpd!=''){
            document.getElementById('cetakan').innerHTML="<br><embed src='"+url+"' width='300%' height='600px'></embed>";
        }
    }   
        function runEffect() {        
            $('#chkpa')._propAttr('checked',false);     
        };  
        
        function runEffect2() {        
            $('#chkppkd')._propAttr('checked',false);
        };
   </script>

<body>
<input type="text" name="tipe_doc" id="tipe_doc" value="<?php echo $jenis ?>" hidden> <!-- untuk cek rka atau dpa -->
<div id="content">
<fieldset style="border-radius: 20px; border: 3px solid black;">
    <legend><h3><b>CETAK <?php echo $jenis ?> SKPD</b></h3></legend>
    <table align="center" style="width:100%;" border="0">
        <tr> 
            <td width="20%">SKPD</td>
            <td width="1%">:</td>
            <td width="79%"><input id="skpd" name="skpd" style="width: 300px;" />
                <input type="text" id="nmskpd" readonly="true" style="width: 400px;border:0" />
            </td>
        </tr>
        <tr> 
            <td width="20%"></td>
            <td width="1%"></td>
            <td width="79%">
                <input type="checkbox" id="rinci" name="rinci" onclick="javascript:cetakbawah();" /> Rincian &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" id="gaji" name="gaji" onclick="javascript:cetakbawah();" /> Tanpa Gaji
            </td>
        </tr>
        <tr> 
            <td width="20%">TANGGAL TTD</td>
            <td width="1%">:</td>
            <td width="79%"><input type="text" id="tgl_ttd" style="width: 300px;" />
            </td>
        </tr>
        <tr hidden> 
            <td width="20%">STATUS ANGGARAN</td>
            <td width="1%">:</td>
            <td width="79%">
                <select class="select" style="display: inline; width: 150px" id="status_anggaran1">
                    <option value="nilai">Nilai murni</option>
                    <option value="nilai_sempurna">Nilai pergeseran</option>
                    <option value="nilai_ubah">Nilai perubahan</option>
                </select>
                <select class="select" style="display: inline; width: 150px" id="status_anggaran2">
                    <option value="nilai">Nilai murni</option>
                    <option value="nilai_sempurna">Nilai pergeseran</option>
                    <option value="nilai_ubah" selected>Nilai perubahan</option>
                </select>
            </td>
        </tr>    
        <tr> 
             <td><input type="checkbox" name="chkpa" id="chkpa" value="1"  onclick="javascript:runEffect2();"/>TTD PA</td>
            <td colspan="2"><input type="checkbox" name="chkppkd" id="chkppkd" value="1" checked="checked" onclick="javascript:runEffect();"/>TTD PKKD</td>
       
         </tr>   
        <tr> 
            <td width="20%">PENGGUNA ANGGARAN</td>
            <td width="1%">:</td>
            <td width="79%"><input type="text" id="ttd1" style="width: 300px;" /> 
            </td>
        </tr>    
        <tr > 
            <td width="20%">PPKD/ SEKDA</td>
            <td width="1%">:</td>
            <td width="79%"><input type="text" id="ttd2" style="width: 300px;" /> 
            </td>
        </tr>   
        <tr> 
            <td width="20%">Cetak Unit Organisasi</td>
            <td width="1%">:</td>
            <td width="79%">
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(0,'skpd','0');return false" >
                <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="cetak"/></a>
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(1,'skpd','0');return false">                    
                <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>

            </td>
        </tr>   
        </table>  
</fieldset> 
<label id="cetakan"></label>
</div>    
</body>
