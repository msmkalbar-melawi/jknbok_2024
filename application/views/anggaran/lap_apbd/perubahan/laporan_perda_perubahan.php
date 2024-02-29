

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
        
        cetakbawah();
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
                panelWidth:400,  
                idField:'id_ttd',  
                textField:'nama',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/cetak_rka/load_tanda_tangan/'+kode,  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},  
                    {field:'nama',title:'Nama',width:400}    
                ]]  
            });   
        });
}
     

    function cek2(cetak){
        var  ctglttd    = $('#tgl_ttd').datebox('getValue');
        var  ttd        = "oPmhkTjD9";
        var  ttd2       = "qoopnsdkwksdlkwsd";
        var  ckdskpd    = "sssss";
        var  tipe_doc   = document.getElementById('tipe_doc').value;
        var  cekbok     = document.getElementById('rinci').checked;
        var  gaji      = document.getElementById('gaji').checked;
        var  status_anggaran1   = document.getElementById('status_anggaran1').value;
        var  status_anggaran2   = document.getElementById('status_anggaran2').value;
        if(gaji==false){
            var cgaji=0;
        }else{
            var cgaji=1;
        }
        if(cekbok==false){
            var url="<?php echo site_url(); ?>cetak_perda/cetak_perda_pergeseran/"+ctglttd+'/'+ttd+'/'+ttd2+'/'+ckdskpd+'/'+cetak+'/hide/'+tipe_doc+'/'+cgaji+'/'+tipe_doc+'/'+status_anggaran1+'/'+status_anggaran2;
        }else{
            var url="<?php echo site_url(); ?>cetak_perda/cetak_perda_pergeseran/"+ctglttd+'/'+ttd+'/'+ttd2+'/'+ckdskpd+'/'+cetak+'/detail/'+tipe_doc+'/'+cgaji+'/'+tipe_doc+'/'+status_anggaran1+'/'+status_anggaran2;            
        }

        
        if (ctglttd == ''){
            alert("Tanggal wajib diisi.");
        } else {
            window.open(url);
        }
    }
    
    function cetakbawah(){
        var ckdskpd = "sssss";
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
            url="<?php echo site_url(); ?>cetak_perda/cetak_perda_pergeseran/2020-1-1/tanpa/tanpa/"+ckdskpd+"/0/hide/"+tipe_doc+"/"+cgaji+"/aa"+'/'+status_anggaran1+'/'+status_anggaran2;; 
        }else{
            url="<?php echo site_url(); ?>cetak_perda/cetak_perda_pergeseran/2020-1-1/tanpa/tanpa/"+ckdskpd+"/0/detail/"+tipe_doc+"/"+cgaji+"/aa"+'/'+status_anggaran1+'/'+status_anggaran2;;
        }
            document.getElementById('cetakan').innerHTML="<br><embed src='"+url+"' width='100%' height='600px'></embed>";
        
    }   
  
   </script>

<body>
<input type="text" name="tipe_doc" id="tipe_doc" value="<?php echo $jenis1 ?>" hidden> <!-- untuk cek rka atau dpa -->
<div id="content">
<fieldset style="border-radius: 20px; border: 3px solid black; width:900px">
    <legend><h3><b>CETAK <?php echo $jenis ?></b></h3></legend>
    <table align="center" style="width:100%;" border="0">
        <tr hidden> 
            <td width="20%">SKPD</td>
            <td width="1%">:</td>
            <td width="79%"><input id="skpd" name="skpd" style="width: 300px;" />
                <input type="text" id="nmskpd" readonly="true" style="width: 400px;border:0" />
            </td>
        </tr>
        <tr > 
            <td width="20%">STATUS ANGGARAN</td>
            <td width="1%">:</td>
            <td width="79%">
                <select class="select" style="display: inline; width: 200px" onchange="javascript:cetakbawah();" id="status_anggaran1">
                    <option value="nilai" selected> murni</option>
                    <option value="_sempurna" > Pergeseran Berjalan</option>
                    <option value="sempurna2"> Pergeseran 1</option>
                    <option value="sempurna3"> Pergeseran 2</option>
                    <option value="sempurna4"> Pergeseran 3</option>
                    <option value="sempurna5"> Pergeseran 4</option>
                    <option value="sempurna6"> Pergeseran 5</option>
                    <option value="nilai_ubah"> perubahan</option>
                </select>
                <select class="select" style="display: inline; width: 200px" onchange="javascript:cetakbawah();" id="status_anggaran2">
                    <option value="nilai"> murni</option>
                    <option value="_sempurna"> Pergeseran Berjalan</option>
                    <option value="sempurna2" > Pergeseran 1</option>
                    <option value="sempurna3" > Pergeseran 2</option>
                    <option value="sempurna4" > Pergeseran 3</option>
                    <option value="sempurna5" > Pergeseran 4</option>
                    <option value="sempurna6" > Pergeseran 5</option>
                    <option value="nilai_ubah" selected> perubahan</option>
                </select>
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
            <td width="20%">TTD 1</td>
            <td width="1%">:</td>
            <td width="79%"><input type="text" id="ttd1" style="width: 300px;" /> 
            </td>
        </tr>    
        <tr hidden> 
            <td width="20%">TTD 2</td>
            <td width="1%">:</td>
            <td width="79%"><input type="text" id="ttd2" style="width: 300px;" /> 
            </td>
        </tr>   
        <tr> 
            <td width="20%">Cetak</td>
            <td width="1%">:</td>
            <td width="79%">
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(0,'skpd','0');return false" >
                <img src="<?php echo base_url(); ?>assets/images/icon/print.png" width="25" height="23" title="cetak"/></a>
                <a class="easyui-linkbutton" plain="true" onclick="javascript:cek2(1,'skpd','0');return false">                    
                <img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png" width="25" height="23" title="cetak"/></a>
                <a class="button" onclick="javascript:cek2(3,'skpd','0');return false"> <i class="fa fa-excel"> Excel</i></a>

            </td>
        </tr>   
        </table>  
        
<label id="cetakan"></label>
</fieldset> 
</div>    
</body>
