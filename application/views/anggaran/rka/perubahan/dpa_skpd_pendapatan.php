

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
    var skpd="";
        
     
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
                {field:'kd_skpd',title:'Kode SKPD',width:100},  
                {field:'nm_skpd',title:'Nama SKPD',width:700}    
            ]],
            onSelect:function(rowIndex,rowData){
                var skpd = rowData.kd_skpd;
                $("#nmskpd").attr("value",rowData.nm_skpd);
                cetakbawah();

                if ($('input[name="chkpa"]:checked').val()=='1'){
                    ttd(skpd);
                }

                if ($('input[name="chkppkd"]:checked').val()=='1'){
                    ttd2(skpd);
                }
                
              
           
           
                }  
            });       
    });        

function ttd(skpd){
           $(function(){
            $('#ttd1').combogrid({  
            panelWidth:900,  
            url: '<?php echo base_url(); ?>/index.php/cetak_rka/load_tanda_tangan/'+skpd,  
                idField:'id_ttd',                    
                textField:'nama',
                mode:'remote',  
                fitColumns:true,  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'NAMA',align:'left',width:200},
                    {field:'jabatan',title:'JABATAN',align:'left',width:200}                               
                ]],
                onSelect:function(rowIndex,rowData){
                nip = rowData.nip;
                
                }   
            });
            
            
      });
}
     
    
function ttd2(){
           $(function(){
              
   
            $('#ttd1').combogrid({  
                panelWidth:900,  
                idField:'id_ttd',  
                textField:'nama',
                fitColumns:true,   
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/cetak_rka/load_tanda_tangan_bud/'+skpd,  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400},
                     {field:'jabatan',title:'JABATAN',align:'left',width:200}           
                ]]  
            });   
                       });
}
   
    
    function cek(cetak){
        var  ctglttd= $('#tgl_ttd').datebox('getValue');
        var  ttd    = $('#ttd1').combogrid('getValue');
        var  ttd2   = "JkjhUYOLnjksdowt";
        var  ckdskpd= $('#skpd').combogrid('getValue');
        var  doc    = document.getElementById('tipe_doc').value;
        var  status_anggaran1    = document.getElementById('status_anggaran1').value;
        var  status_anggaran2    = document.getElementById('status_anggaran2').value;

        url="<?php echo site_url(); ?>preview_pendapatan_pergeseran/"+ctglttd+'/'+ttd+'/'+ttd2+'/'+ckdskpd+'/'+cetak+'/'+doc+'/'+status_anggaran1+'/'+status_anggaran2+'/RKA Pendapatan Penyusunan-'+ckdskpd;
        
        if (ckdskpd=='' || ctglttd==''){
            alert("Pilih Nama SKPD Terlebih Dahulu")
        } else if (ttd==''){
            alert("Pilih Penandatangan Terlebih Dahulu")
        } else {
            window.open(url);
        }
    }
    
    function cetakbawah(){
        var ckdskpd = $('#skpd').combogrid('getValue');
        var doc     = document.getElementById('tipe_doc').value;
        var  status_anggaran1    = document.getElementById('status_anggaran1').value;
        var  status_anggaran2    = document.getElementById('status_anggaran2').value;
        url="<?php echo site_url(); ?>preview_pendapatan_pergeseran/2020-1-1/tanpa/tanpa/"+ckdskpd+"/0/"+doc+"/"+status_anggaran1+"/"+status_anggaran2+"/ Pendapatan Penyusunan-"+ckdskpd;        
        if(ckdskpd!=''){
            document.getElementById('cetakan').innerHTML="<br><embed src='"+url+"' width='290%' height='600px'></embed>";
        }
    }
  
         function runEffect() {        
            $('#chkpa')._propAttr('checked',false);
            var  skpd    = $('#skpd').combogrid('getValue');
            ttd2(skpd);
        }
        
        function runEffect2() {        
            $('#chkppkd')._propAttr('checked',false);
            var  skpd    = $('#skpd').combogrid('getValue');
            ttd(skpd);
        }; 
   </script>

<input type="text" name="tipe_doc" id="tipe_doc" value="<?php echo $jenis ?>" hidden> <!-- untuk cek rka atau dpa -->
<div id="content" align=""> 
<fieldset style="border-radius: 20px; border: 3px solid black;">
    <legend><h3><b>CETAK <?php echo $jenis ?> SKPD Pendapatan</b></h3></legend>
    
    <table align="center" style="width:100%;" border="0">
        <tr> 
            <td width="20%">SKPD</td>
            <td width="1%">:</td>
            <td width="79%"><input id="skpd" name="skpd" style="width: 300px;" />
                <input type="text" id="nmskpd" readonly="true" style="width: 400px;border:0" />
            </td>
        </tr>
        <tr hidden> 
            <td width="20%">STATUS ANGGARAN</td>
            <td width="1%">:</td>
            <td width="79%">
                <select class="select" style="display: inline; width: 150px" id="status_anggaran1"  onchange="javascript:cetakbawah()">
                    <option  value="nilai">Nilai murni</option>
                    <option hidden value="nilai_sempurna">Nilai pergeseran</option>
                    <option hidden value="nilai_ubah">Nilai perubahan</option>
                </select>
                <select class="select" style="display: inline; width: 150px" id="status_anggaran2" onchange="javascript:cetakbawah()">
                    <option hidden value="nilai">Nilai murni</option>
                    <option  value="nilai_sempurna" >Nilai pergeseran</option>
                    <option value="nilai_ubah" selected>Nilai perubahan</option>
                </select>
            </td>
        </tr>  
        <tr> 
            <td width="20%">TANGGAL TTD</td>
            <td width="1%">:</td>
            <td width="79%"><input type="text" id="tgl_ttd" style="width: 300px;" />
            </td>
        </tr> 
        <tr> 
             <td><input type="checkbox" name="chkpa" id="chkpa" value="1" checked onclick="javascript:runEffect2();"/>TTD PA</td>
            <td colspan="2"><input type="checkbox" name="chkppkd" id="chkppkd" value="1"  onclick="javascript:runEffect();"/>TTD PKKD/ SEKDA</td>
       
         </tr>          
        <tr> 
            <td width="20%">TTD </td>
            <td width="1%">:</td>
            <td width="79%"><input type="text" id="ttd1" style="width: 300px;" /> 
            </td>
        </tr>    
        <tr hidden > 
            <td width="20%">TTD 2</td>
            <td width="1%">:</td>
            <td width="79%"><input type="text" id="ttd2" style="width: 300px;" /> 
            </td>
        </tr>   
        <tr> 
            <td width="20%">Cetak</td>
            <td width="1%"></td>
            <td width="79%">
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(0);" >
                <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="cetak"/></a>
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek(1);">                    
                <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>

            </td>
        </tr>   
        </table>             
    </fieldset>
<label id="cetakan"></label>  
</div>  
