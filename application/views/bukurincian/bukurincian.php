<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>


<link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() {

        $.ajax({
            url: '<?php echo base_url(); ?>index.php/rka/config_skpd',
            type: "POST",
            dataType: "json",
            success: function(data) {
                $("#skpd").attr("value", data.kd_skpd);
                $("#nmskpd").attr("value", data.nm_skpd);
            }
        });

        $('#pptk').combogrid({  
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
        
        $('#pengguna').combogrid({  
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

        $('#jnsang').combogrid({  
                    panelWidth:400,  
                    idField:'kode',  
                    textField:'nama',  
                    mode:'remote',
                    url:'<?php echo base_url(); ?>index.php/rka_ro/load_jang/',  
                    columns:[[  
                        {field:'nama',title:'Nama',width:400}    
                    ]],
                    onSelect:function(rowIndex,rowData){
                         kd_jang = rowData.kode;
                         jangkas(kd_jang);
                    }
        });
         
    
        // sub kegiatan
        $.ajax({
            url: '<?php echo base_url(); ?>index.php/bukurincian/BukuRincianController/subkegiatan',
            type: "POST",
            dataType: "json",
            success: function(data) {
                $('#subkegiatan').append(`<option value=""> 
                                      -- Pilih Sub Kegiatan -- 
                                  </option>`); 
                $.each( data, function( key, value ) {
                    $('#subkegiatan').append(`<option value="${value.kd_sub_kegiatan}"> 
                    ${value.kd_sub_kegiatan} -- ${value.nm_sub_kegiatan}
                                  </option>`); 
            });
            }
        });

        $('#subkegiatan').change(function(){
            let kd_sub_kegiatan = this.value;

            $.ajax({
            url: '<?php echo base_url(); ?>index.php/bukurincian/BukuRincianController/subkoderekbelanja',
            data:{
                kd_sub_kegiatan:kd_sub_kegiatan
            },
            type: "POST",
            dataType: "json",
            success: function(data) {
                $('#kdrekbelnja').empty();
                $('#kdrekbelnja').append(`<option value=""> 
                                      -- Pilih Rekening Belanja -- 
                                  </option>`); 
                $.each( data, function( key, value ) {
                    $('#kdrekbelnja').append(`<option value="${value.kd_rek6}"> 
                    ${value.kd_rek6} -- ${value.nm_rek6}
                                  </option>`); 
            });
            }
        });
        });

    });

    // function get_skpd()
    //     {
        
    //         $.ajax({
    //             url:'<?php echo base_url(); ?>index.php/sp2d/config_skpd',
    //             type: "POST",
    //             dataType:"json",                         
    //             success:function(data){
    //                                     $("#sskpd").attr("value",data.kd_skpd);
    //                                     $("#nmskpd").attr("value",data.nm_skpd);
    //                                    // $("#skpd").attr("value",rowData.kd_skpd);
    //                                     kdskpd = data.kd_skpd;
                                        
    //                                   }                                     
    //         });
             
    //     }

    function cetak(ctk){
           let kd_skpd = $('#skpd').val();
           let nm_skpd = $('#nmskpd').val(); 
           let kd_sub_kegiatan = $('#subkegiatan').val(); // Jquery
           let kd_rek6 = document.getElementById('kdrekbelnja').value; // Javasrcipt
           let jns_ang = $('#jnsang').val();
           let periode1 = $('#tgl1').val();
           let periode2 = $('#tgl2').val();
           let pptk = $('#pptk').val();
           let pengguna = $('#pengguna').val();
           let lemparan = '?jnscetak='+ctk+'&kd_sub_kegiatan=' +kd_sub_kegiatan+ '&kd_rek6=' +kd_rek6+'&jns_ang=' +jns_ang+ '&periode1='+periode1+'&dan'+'&periode2='+periode2+'&pptk=' +pptk+'&pengguna=' +pengguna;
          let url = "<?php echo site_url(); ?>index.php/bukurincian/BukuRincianController/Cetaklaporan" + lemparan;
          window.open(url,'_blank');
          window.send();
        }
    
</script>


<div id="content" align="center" style="background: white">
    <h3 align="center"><b>CETAK RINCIAN KARTU KENDALI</b></h3>
    <!--  <fieldset style="width: 70%;"> -->
    <table align="center" style="width:100%;" border="0">
        <tr>
            <td colspan="3">
                <div id="div_skpd">
                    <table style="width:100%;" border="0">
                        <td width="20%">SKPD</td>
                        <td width="1%">:</td>
                        <td width="79%"><input id="skpd" name="skpd" style="width: 200px;" />&ensp;
                            <input type="text" id="nmskpd" readonly="true" style="width: 400px;border:0" />
                        </td>
                    </table>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="3">
                <div id="div-subkegiatan">
                    <table style="width:100%;" border="0">
                        <td width="20%">Sub Kegiatan</td>
                        <td width="1%">:</td>
                        <td width="79%">
                            <select name="subkegiatan" id="subkegiatan" style="width: 600px;">
                            </select>
                        </td>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div id="div-kdrekbelnja">
                    <table style="width:100%;" border="0">
                        <td width="20%">Kode Rekening Belanja</td>
                        <td width="1%">:</td>
                        <!-- style="width: 200px;"  -->
                        <td width="79%">
                        <select name="kdrekbelnja" id="kdrekbelnja" style="width: 600px;">
                            
                            </select>
                        </td>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
        <tr>
            <td colspan="3">
                <div id="div-jnsang">
                    <table style="width:100%;" border="0">
                        <td width="20%">Jenis Anggaran</td>
                        <td width="1%">:</td>
                        <td width="79%">
                            <select name="jnsang" id="jnsang" style="width: 150px;">
                            </select>
                        </td>
                    </table>
                </div>
            </td>
        </tr>
        <td colspan="3">
                <div id="div-periode">
                    <table style="width:100%;" border="0">
                    <td width="20%">PERIODE</td>
                        <td width="1%">:</td>
                        <td width="79%"><input type="date" id="tgl1" style="width: 200px;" /> s.d. <input type="date" id="tgl2" style="width: 200px;" />
                        </td>
                    </table>
                </div>
            </td>
        </tr>
       
        <tr>
            <td colspan="3">
                <div id="div_bend">
                    <table style="width:100%;" border="0">
                        <td width="20%">TANGGAL TTD</td>
                        <td width="1%">:</td>
                        <td><input type="date" id="tgl_ttd" style="width: 200px;" />
                        </td>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div id="div-pptk">
                    <table style="width:100%;" border="0">
                        <td width="20%">PPTK</td>
                        <td width="1%">:</td>
                        <td width="79%">
                            <select name="pptk" id="pptk" style="width: 250px;">
                            </select>
                        </td>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div id="div-pengguna">
                    <table style="width:100%;" border="0">
                        <td width="20%">Pengguna Anggaran</td>
                        <td width="1%">:</td>
                        <td width="79%">
                            <select name="pengguna" id="pengguna" style="width: 250px;">
                            </select>
                        </td>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <button class="button-biru" plain="true" value="layar" onclick="javascript:cetak(this.value);"><i class="fa fa-television"></i> Cetak Layar </button>
                <button class="button-kuning" plain="true" value="pdf" onclick="javascript:cetak(this.value);"><i class="fa fa-pdf"></i> Cetak PDF </button>
            </td>
        </tr>
    </table>
</div>