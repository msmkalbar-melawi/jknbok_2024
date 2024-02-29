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
  


</head>
<script type="text/javascript">
     
    var kode = '';
    var giat = '';
    var nomor= '';
    var judul= '';
    var cid = 0;
    var lcidx = 0;
    var lcstatus = '';
    var ctk = '';
        
   
        $(function(){
            $('#ttd1').combogrid();
        $('#tgl_ttd').datebox({  
            required:true,
            formatter :function(date){
                var y = date.getFullYear();
                var m = date.getMonth()+1;
                var d = date.getDate();
                return y+'-'+m+'-'+d;
            }
        });
        
        var skpd="<?php  
                        {$skpd = $this->session->userdata('type');} 
                        if($skpd=='1'){
                            echo $skpd= $this->uri->segment(3); 
                        }else{
                            echo $skpd = $this->session->userdata('kdskpd');
                        }?>";
        
    
        

        }); 

  function ttd(){
        var skpd="<?php  
                        {$skpd = $this->session->userdata('type');} 
                        if($skpd=='1'){
                            echo $skpd= $this->uri->segment(3); 
                        }else{
                            echo $skpd = $this->session->userdata('kdskpd');
                        }?>";
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
        var skpd="<?php  
                        {$skpd = $this->session->userdata('type');} 
                        if($skpd=='1'){
                            echo $skpd= $this->uri->segment(3); 
                        }else{
                            echo $skpd = $this->session->userdata('kdskpd');
                        }?>";
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
                    {field:'nama',title:'Nama',width:200},
                     {field:'jabatan',title:'JABATAN',align:'left',width:200}           
                ]]  
            });   
                       });
}  
    
        function cetak_semua()
        { 
        var skpd="<?php  
                        {$skpd = $this->session->userdata('type');} 
                        if($skpd=='1'){
                            echo $skpd= $this->uri->segment(3); 
                        }else{
                            echo $skpd = $this->session->userdata('kdskpd');
                        }?>";
                        
           var  ctglttd = $('#tgl_ttd').datebox('getValue');
           var  ttd = $('#ttd1').combogrid('getValue');
           if (ttd=='' || ctglttd==''){
           alert("Penanda tangan 1 atau tanggal Tanda tangan tidak boleh kosong"); exit();
           }

            $.ajax({
                
                url:'<?php echo base_url(); ?>index.php/anggaran_murni/cetak_Semua/'+skpd,            
                type: "POST",
                dataType:"json",                         
                success:function(data){
                      $.each(data, function(i,n){
                        var giat=n['kd_kegiatan'];
                        var urlx="<?php echo site_url(); ?>cetak_rka/preview_rincian_belanja_skpd_pergeseran/"+skpd+"/"+giat+"/1";
                        openWindow( urlx );
                         
                    });
                }
            });
        }


 function openWindow( url ){

           var  ctglttd = $('#tgl_ttd').datebox('getValue');
           var  ttd = $('#ttd1').combogrid('getValue');
           var  ttd_2 = "ss";
           var ttd1 = ttd.split(" ").join("a");
           var ttd2 = "sdsdwqefDSdfdR";
           var jns_an = "<?php echo $jenis ?>";
           var atas   =  document.getElementById('atas').value;
           var bawah   =  document.getElementById('bawah').value;
           var kiri   =  document.getElementById('kiri').value;
           var kanan   =  document.getElementById('kanan').value;
           var status_anggaran1   =  document.getElementById('status_anggaran1').value;
           var status_anggaran2   =  document.getElementById('status_anggaran2').value;

           if (ttd=='' || ctglttd==''){
           alert("Penanda tangan 1 atau tanggal Tanda tangan tidak boleh kosong");
           } else {
            l1 = '/'+atas+'/'+bawah+'/'+kiri+'/'+kanan+'/'+status_anggaran1+'/'+status_anggaran2+'/'+jns_an;
            l1 = l1.trim();
            lc = '?tgl_ttd='+ctglttd+'&ttd1='+ttd1+'&ttd2='+ttd2+'';
            window.open(url+l1+lc,'_blank');
            window.focus();
            }
          
     } 
          
         function runEffect() {        
            $('#chkpa')._propAttr('checked',false);
            ttd2();
        }
        
        function runEffect2() {        
            $('#chkppkd')._propAttr('checked',false);
            ttd();
        }   

</script>
<body>

<div id="content"> 
<h1>DAFTAR KEGIATAN</h1>

    <table width="100%" cellpadding="5">
        <tr> 
             <td><input type="checkbox" name="chkpa" id="chkpa" value="1" onclick="javascript:runEffect2();"/>TTD PA</td>
            <td colspan="2"><input type="checkbox" name="chkppkd" id="chkppkd" value="1"  onclick="javascript:runEffect();"/>TTD PKKD/ SEKDA</td>
       
         </tr>  
        <tr>
            <td width="10%">Penandatangan</td>
            <td width="90%">: <input type="text" name="ttd1" id="ttd1" ></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: <input type="text" name="tgl_ttd" id="tgl_ttd"></td>
        </tr>
        <tr hidden > 
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
                    <option value="nilai_sempurna" >Nilai pergeseran</option>
                    <option value="nilai_ubah" selected>Nilai perubahan</option>
                </select>
            </td>
        </tr>      
        <tr hidden>
            <td></td>
            <td>Kiri  : &nbsp;<input type="number" id="kiri" name="kiri" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
                Kanan : &nbsp;<input type="number" id="kanan" name="kanan" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
                Atas  : &nbsp;<input type="number" id="atas" name="atas" style="width: 50px; border:1" value="15" /> &nbsp;&nbsp;
                Bawah : &nbsp;<input type="number" id="bawah" name="bawah" style="width: 50px; border:1" value="15" /></td>
        </tr>
        <tr>
            <td colspan="2"><button type="text" class="button-abu" onclick="javascript:cetak_semua()"><i class="fa fa-pdf"></i> Cetak Keseluruhan </button></td>
        </tr>
    </table>
<br>
    <table width="100%">
        <tr>
            <td bgcolor="#cccccc" width="15%" align="center">KODE KEGIATAN</td>
            <td bgcolor="#cccccc" width="60%" align="center">NAMA KEGIATAN</td>
            <td bgcolor="#cccccc" width="25%" align="center">#</td>
        </tr>
    <?php $sql=$this->db->query("SELECT left(a.kd_sub_kegiatan,12) giat, (select nm_kegiatan from ms_kegiatan where kd_kegiatan=left(a.kd_sub_kegiatan,12) ) nm_kegiatan from trdrka a  where left(kd_skpd,17)=left('$skpd',17) AND left(kd_rek6,1)='5'  GROUP BY
left(a.kd_sub_kegiatan,12)"); 
        foreach($sql->result() as $abc) :
    ?>
        <tr>
            <td width="15%" align="center"><?php echo $abc->giat; ?></td>
            <td width="60%" align="left"><?php echo $abc->nm_kegiatan; ?></td>
            <td width="25%" align="center"> 
                <a href="<?php echo site_url(); ?>cetak_rka/preview_rincian_belanja_skpd_pergeseran/<?php echo $skpd ?>/<?php echo $abc->giat; ?>/<?php echo '0';?> "class="button" plain="true" onclick="javascript:openWindow(this.href);return false"><img src="<?php echo base_url(); ?>assets/images/icon/print.png"  width="25" height="23" title="cetak"></a> 
                <a href="<?php echo site_url(); ?>cetak_rka/preview_rincian_belanja_skpd_pergeseran/<?php echo $skpd ?>/<?php echo $abc->giat; ?>/<?php echo '1';?> "class="button" plain="true" onclick="javascript:openWindow(this.href);return false"><img src="<?php echo base_url(); ?>assets/images/icon/print_pdf.png"  width="25" height="23" title="cetak"></a>
                <a href="<?php echo site_url(); ?>cetak_rka/preview_rincian_belanja_skpd_pergeseran/<?php echo $skpd ?>/<?php echo $abc->giat; ?>/<?php echo '2';?> "class="button" plain="true" onclick="javascript:openWindow(this.href);return false"><img src="<?php echo base_url(); ?>assets/images/icon/excel.jpg"  width="25" height="23" title="cetak"></a>
                <a href="<?php echo site_url(); ?>cetak_rka/preview_rincian_belanja_skpd_pergeseran/<?php echo $skpd ?>/<?php echo $abc->giat; ?>/<?php echo '3';?> "class="button" plain="true" onclick="javascript:openWindow(this.href);return false"><img src="<?php echo base_url(); ?>assets/images/icon/word.jpg"  width="25" height="23" title="cetak"></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
</div>

</body>

</html>