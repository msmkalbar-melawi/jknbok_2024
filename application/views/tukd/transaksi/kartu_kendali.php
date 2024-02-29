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
    
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
   
    <script type="text/javascript"> 
    var nip='';
    var kdskpd='';
    var kdrek5='';
    
     $(document).ready(function() {
            $("#accordion").accordion();            
            $( "#dialog-modal" ).dialog({
                height: 400,
                width: 800            
            });
             get_skpd();               
        });   
    
    
    $(function(){  
            $('#ttd').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/sp2d/load_ttd/PPTK',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nama").attr("value",rowData.nama);
           } 
            });          
         });
    
        $(function(){  
            $('#ttd2').combogrid({  
                panelWidth:600,  
                idField:'nip',  
                textField:'nip',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/sp2d/load_ttd/PA',  
                columns:[[  
                    {field:'nip',title:'NIP',width:200},
                    {field:'nama',title:'Nama',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nama2").attr("value",rowData.nama);
           } 
            });          
         });
         $(function(){  
            $('#kdgiat').combogrid({  
                panelWidth:600,  
                idField:'kd_sub_kegiatan',  
                textField:'kd_sub_kegiatan',  
                mode:'remote',
                url:'<?php echo base_url(); ?>index.php/sp2d/load_giat_trans',  
                columns:[[  
                    {field:'kd_sub_kegiatan',title:'Kode',width:200},
                    {field:'nm_sub_kegiatan',title:'Nama Kegiatan',width:400}
                ]],  
           onSelect:function(rowIndex,rowData){
               $("#nmgiat").attr("value",rowData.nm_sub_kegiatan);
           } 
            });          
         });
        $(function(){   
         $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            }
        });
        }); 
    function validate1(){
        var bln1 = document.getElementById('bulan1').value;
        
    }
    
    function get_skpd()
        {
        
            $.ajax({
                url:'<?php echo base_url(); ?>index.php/sp2d/config_skpd',
                type: "POST",
                dataType:"json",                         
                success:function(data){
                                        $("#sskpd").attr("value",data.kd_skpd);
                                        $("#nmskpd").attr("value",data.nm_skpd);
                                       // $("#skpd").attr("value",rowData.kd_skpd);
                                        kdskpd = data.kd_skpd;
                                        
                                      }                                     
            });
             
        }
    
        
        function cetak(ctk)
        {
            var spasi  = document.getElementById('spasi').value; 
            var nip     = nip;
            var skpd   = kdskpd; 
            var  giat = $('#kdgiat').combogrid('getValue');
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            var  ttd = $('#ttd').combogrid('getValue');
            ttd = ttd.split(" ").join("123456789");
            var  ttd2 = $('#ttd2').combogrid('getValue');
            ttd2 = ttd2.split(" ").join("123456789");
            var url    = "<?php echo site_url(); ?>cetak_kartukendali/cetak_kartu_kendali";  
            if(giat==''){
            alert('Pilih Kegiatan dulu')
            exit()
            }
            if(ctglttd==''){
            alert('Pilih Tanggal tanda tangan dulu')
            exit()
            }
            if(ttd==''){
            alert('Pilih Bendahara Pengeluaran dulu')
            exit()
            }
            if(ttd2==''){
            alert('Pilih Pengguna Anggaran dulu')
            exit()
            }
            window.open(url+'/'+skpd+'/'+giat+'/'+ctk+'/'+ttd+'/'+ttd2+'/'+ctglttd+'/'+spasi, '_blank');
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



<h3>KARTU KENDALI SUB KEGIATAN OPD/UNIT</h3>
<div id="accordion">
    
    <p align="right">         
        <table id="sp2d" title="Kartu Kendali Sub Kegiatan" style="width:922px;height:200px;" >  
        <tr >
            <td width="20%" height="40" ><B>SKPD</B></td>
            <td width="80%"><input id="sskpd" name="sskpd" style="width: 150px;border: none" />&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 400px; border:0;" /></td>
        </tr>
       <tr>
                <td colspan="4">
                <div id="div_tgl">
                        <table style="width:100%;" border="0">
                            <td width="20%">Kode Sub Kegiatan</td>
                            <td><input type="text" id="kdgiat" style="width: 200px;" /> &nbsp;&nbsp;
                            <input type="nmgiat" id="nmgiat" readonly="true" style="width: 300px;border:0" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
         <tr>
                <td colspan="4">
                <div id="div_tgl">
                        <table style="width:100%;" border="0">
                            <td width="20%">Tanggal TTD</td>
                            <td><input type="text" id="tgl_ttd" style="width: 100px;" /> 
                            </td> 
                        </table>
                </div>
                </td> 
            </tr>
        <tr>
        <td colspan="4">
                <div id="div_bend">
                        <table style="width:100%;" border="0">
                            <td width="20%">PPTK</td>
                            <td><input type="text" id="ttd" style="width: 200px;" /> &nbsp;&nbsp;
                            <input type="nama" id="nama" readonly="true" style="width: 200px;border:0" /> 
                            
                            </td> 
                        </table>
                </div>
        </td> 
        </tr>
        
        <tr>
        <td colspan="4">
                <div id="div_bend2">
                        <table style="width:100%;" border="0">
                            <td width="20%">Pengguna Anggaran</td>
                            <td><input type="text" id="ttd2" style="width: 200px;" /> &nbsp;&nbsp;
                            <input type="nama2" id="nama2" readonly="true" style="width: 200px;border:0" /> 
                            
                            </td> 
                        </table>
                </div>
        </td> 
        </tr>
        <tr>
        <td colspan="4">
                <div id="div_bend2">
                        <table style="width:100%;" border="0">
                            <td width="20%">Spasi</td>
                            <td><input type="number" id="spasi" style="width: 100px;" value="1"/> 
                            
                            </td> 
                        </table>
                </div>
        </td> 
        </tr>
        <tr >
            <td colspan="2" align="center">
            <button class="button-hitam"  plain="true" onclick="javascript:cetak(0);"><i class="fa fa-television"></i> Layar </button>
              <button class="button-kuning"  plain="true" onclick="javascript:cetak(1);"><i class="fa fa-television"></i> PDF </button>
           
            </td>
        </tr>
        
        </table>                      
    </p> 
    

</div>
</div>

    
</body>

</html>