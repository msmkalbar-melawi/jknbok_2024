<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="jaka" />
    <script type="text/javascript" src="<?php base_url();?>script/jquery.js"></script>
    <script type="text/javascript">
    function addText() {
      var x = document.getElementById("kd_skpd");
      var y = document.getElementById("kd_skpd1");
      getCmb = x.value;
      y.value = getCmb;
    }
    function addText1() {
      var p = document.getElementById("kd_urusan");
      var w = document.getElementById("kd_urusan1");
      getCmb = p.value;
      w.value = getCmb;      
    }
    function addText2() {
      var x = document.getElementById("kd_skpd");
      var y = document.getElementById("kd_skpd1");
      var p = document.getElementById("kd_urusan");
      var w = document.getElementById("kd_urusan1");
      y.value= p.value+'.'+x.value; 
    }
    function addgiat() {
      var x = document.getElementById("gt");
      var y = document.getElementById("ngt");
      x.value = y.value
      
    }
    
    </script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script >           
        function validate_keg(){
        var cskpd1 = document.getElementById('kd_skpd').value;
        var x = document.getElementById("kd_skpd");
        var y = document.getElementById("kd_skpd1");
        getCmb = x.value;
        y.value = getCmb;        
         //alert(cskpd1);     
           $(function(){
            $('#gt').combogrid({  
                panelWidth:700,  
                url: '<?php echo base_url(); ?>/index.php/rka/kegiatan/'+cskpd1,  
                    idField:'kd_kegiatan',  
                    textField:'nm_kegiatan',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'kd_kegiatan',title:'kode',width:40},  
                        {field:'nm_kegiatan',title:'nama',align:'left',width:200} 
                          
                    ]],onSelect: function(){
                        addgiat(); 
                    }  
                });
           }); 
        }

        
         
          $(function(){
            $('#gt').combogrid({  
                panelWidth:700,  
                url: '<?php echo base_url(); ?>/index.php/rka/kegiatan',  
                    idField:'kd_kegiatan',  
                    textField:'nm_kegiatan',
                    mode:'remote',  
                    fitColumns:true,  
                    columns:[[  
                        {field:'kd_kegiatan',title:'kode',width:40},  
                        {field:'nm_kegiatan',title:'nama',align:'left',width:200} 
                          
                    ]]  
                });
           });
           
        function addgiat() {
            var x = document.getElementById("gt");
            var y = document.getElementById("ngt");
            x.value = y.value
        } 
    </script>

	<title>Untitled 1</title>
</head>

<body>

<div id="content">

	<h1><?php echo $page_title; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo site_url(); ?>/kegiatan_terpilih"><img src="<?php echo base_url(); ?>assets/images/icon/back.png" width="25" height="23" title="Kembali"/></a></h1>

	<?php echo form_open('kegiatan_terpilih/tambah', array('class' => 'basic')); ?>
    
    <table class="form">
    
        <tr>
    	  <td width="14%"><label>Skpd </label>         
          <td width="86%">
            
           <?php
                $lctam = '<select name="kd_skpd"id="kd_skpd" onchange="javascript: validate_keg();">';
                $lctam1 = '<option value="">...Pilih Skpd...</option>';
                $lc = "select kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
                $query = $this->db->query($lc);
                $data=$query->result();
                foreach($data as $ckdskpd){    
                 
                        $lctam1 = $lctam1."<option value=\"$ckdskpd->kd_skpd\" >$ckdskpd->kd_skpd | $ckdskpd->nm_skpd </option>";
                }
                $lctam1 = $lctam1."</select></label>
            </td>";
                echo $lctam.$lctam1;
            ?>
        </tr>
        <tr>
    	  <td width="14%"><label>Urusan </label>        
          <td width="86%">
            
           <?php
                $lctam = '<select name="kd_urusan"id="kd_urusan" onchange="javascript: addText1();">';
                $lctam1 = '<option value="">...Pilih urusan...</option>';
                $lc = "select kd_urusan,nm_urusan from ms_urusan order by kd_urusan";
                $query = $this->db->query($lc);
                $data=$query->result();
                foreach($data as $ckdurusan){    
                 
                        $lctam1 = $lctam1."<option value=\"$ckdurusan->kd_urusan\" >$ckdurusan->kd_urusan | $ckdurusan->nm_urusan </option>";
                }
                $lctam1 = $lctam1."</select></label>
            </td>";
                echo $lctam.$lctam1;
            ?>
        </tr>        	
        <tr>
          <td>&nbsp;</td>
       	  <td><input name="kd_skpd1" type="text" id="kd_skpd1" value="<?php echo set_value('kd_skpd1'); ?>" size="20" /> <?php echo form_error('kd_skpd1'); ?>
              <input name="kd_urusan1" type="text" id="kd_urusan1" value="<?php echo set_value('kd_urusan1'); ?>" size="20" /> <?php echo form_error('kd_urusan1'); ?></td>  
            
        </tr>        
       
        <tr>
          <td ><label>Kegiatan </label>        	  
             <td><input  id="gt" name="gt" style="width:600px;height:10px;"  />
                       
        </tr>   
                        
        <tr>
          <td>&nbsp;</td>
            <td><input name="simpan" type="submit" id="simpan" value="Simpan" class="btn" /><input name="reset" type="reset" id="reset" value="Reset" class="btn" /></td>
            
        </tr>
        
    </table>
    <?php echo form_close(); ?>
    
</div>


</body>
</html>