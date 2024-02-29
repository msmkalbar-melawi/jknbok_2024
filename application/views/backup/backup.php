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

	function rekal(){
		document.getElementById('load').style.visibility='visible';
        var nama = document.getElementById('nama').value;
        var dir = document.getElementById('dir').value;  
		var database = document.getElementById('database').value;

		$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1',nama:nama,dir:dir,database:database}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/akuntansi/proses_backup",
			success:function(data){
                        status = data.pesan;
                        if (status=='1'){
                            alert('Data Berhasil Disimpan!');         
                        } else {
                            alert('Gagal Hapus');
                        }
				document.getElementById('load').style.visibility='hidden';
						
                 }
		 });
		});
	}
	
	function rekal_restore(){
		document.getElementById('load_restore').style.visibility='visible';
		var nama = document.getElementById('fileToUpload1').value;
        var database = document.getElementById('database_restore').value;
        var dir = document.getElementById('dir_restore').value;  
		$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1',nama:nama,dir:dir,database:database}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/akuntansi/proses_restore/",
			success:function(data){
                        status = data.pesan;
                        if (status=='1'){
                            alert('Data Berhasil di Restore');         
                        } else {
                            alert('Gagal Hapus');
                        }
				document.getElementById('load_restore').style.visibility='hidden';
						
                 }
		 });
		});
	}
	
	function ajaxFileUpload(lc)
	{
	
	var did = document.getElementById('fileToUpload1').value;
	
	$(function(){      
		 $.ajax({
			type: 'POST',
			data: ({nomor:'1',did:did}),
			dataType:"json",
			url:"<?php echo base_url(); ?>index.php/akuntansi/proses_restore/",
			success:function(data){
                        status = data.pesan;
                        if (status=='1'){
                            alert('Data Berhasil di Backup');         
                        } else {
                            alert('Gagal Hapus');
                        }
				document.getElementById('load').style.visibility='hidden';
						
                 }
		 });
		});
		
	}
	function getFolder(){
  return showModalDialog("<?php echo base_url(); ?>tukd/backup/backcup.php","","width:400px;height:400px;resizeable:yes;");
}



var currentFolder="";
function GetDriveList(){
  var fso, obj, n, e, item, arr=[];
try { 
  fso = new ActiveXObject("Scripting.FileSystemObject"); 
} 
catch(er) {
  alert('Could not load Drives. The ActiveX control could not be started.');
  cancelFolder();
} 

  e = new Enumerator(fso.Drives); 
  for(;!e.atEnd();e.moveNext()){ 
    item = e.item();
    obj = {letter:"",description:""};
    obj.letter = item.DriveLetter;
    if (item.DriveType == 3) obj.description = item.ShareName;
    else if (item.IsReady) obj.description = item.VolumeName;
    else obj.description = "[Drive not ready]";
    arr[arr.length]=obj;
  } 
  return(arr);
}
function GetSubFolderList(fld){
  var e, arr=[];
  var fso = new ActiveXObject("Scripting.FileSystemObject");
  var f = fso.GetFolder(fld.toString());
  var e = new Enumerator(f.SubFolders);
  for(;!e.atEnd();e.moveNext()){ 
    arr[arr.length]=e.item().Name;
  }
  return(arr);
}
function loadDrives(){
  var drives=GetDriveList(),list="";
  for(var i=0;i<drives.length;i++){
    list+="<div onclick=\"loadList('"+drives[i].letter+':\\\\\')" class="folders" onmouseover="highlight(this)" onmouseout="unhighlight(this)">'+drives[i].letter+':\\ - '+ drives[i].description+'</div>';
  }
  document.getElementById("path").innerHTML='<a href="" onclick="loadDrives();return false" title="My Computer">My Computer</a>\\';
  document.getElementById("list").innerHTML=list;
  currentFolder="";
}
function loadList(fld){
  var path="",list="",paths=fld.split("\\");
  var divPath=document.getElementById("path");
  var divList=document.getElementById("list");
  for(var i=0;i<paths.length-1;i++){
    if(i==paths.length-2){
      path+=paths[i]+' \\';
    }else{
      path+="<a href=\"\" onclick=\"loadList('";
      for(var j=0;j<i+1;j++){
        path+=paths[j]+"\\\\";
      }
      path+='\');return false">'+paths[i]+'</a> \\ ';
    }
  }
  divPath.innerHTML='<a href="" onclick="loadDrives();return false">My Computer</a> \\ '+path;
  divPath.title="My Computer\\"+paths.toString().replace(/,/g,"\\");
  currentFolder=paths.toString().replace(/,/g,"\\");

  var subfolders=GetSubFolderList(fld);
  for(var j=0;j<subfolders.length;j++){
    list+="<div onclick=\"loadList('"+(fld+subfolders[j]).replace(/\\/g,"\\\\")+'\\\\\')" onmouseover="highlight(this)" onmouseout="unhighlight(this)" title="'+subfolders[j]+'" class="folders">'+subfolders[j]+"</div>";
  }
  divList.innerHTML=list;
  resizeList();
  divPath.scrollIntoView();
}
function resizeList(){
  var divList=document.getElementById("list");
  var divPath=document.getElementById("path");
  if(document.body.clientHeight>0 && divPath.offsetHeight>0){
    divList.style.height=document.body.clientHeight-divPath.scrollHeight;
  }
}
function highlight(div){
  div.className="folderButton";
}
function unhighlight(div){
  div.className="folders";
}
function selectFolder(){
  window.returnValue=currentFolder;
  window.close();
}
function cancelFolder(){
  window.returnValue="";
  window.close();
}

    </script>

</head>
<body>

<div id="content">

<div id="accordion">

<h3>PROSES BACKUP</h3>
    <div>
    <p >         
        <table id="sp2d" title="Proses Backup" style="width:870px;height:300px;" > 
		<tr>
                <td><b> &nbsp;&nbsp;&nbsp; Nama Database : </b> &nbsp;&nbsp;&nbsp;&nbsp; <input type="text" id="database"   name="database" style="width:250px;" value="simakdaskpd_2015"/></td>
				
           </tr>
		<tr>
                <td><b> &nbsp;&nbsp;&nbsp; Nama File : </b> &nbsp;&nbsp;&nbsp;&nbsp; <input type="text" id="nama"   name="nama" style="width:250px;"/></td>
				
           </tr>
		   <tr>
				<td><b>&nbsp;&nbsp;&nbsp; Pilih Directory : </b><select name="dir" id="dir" >
					 <option value="">PILIH DIRECTORY</option>
					 <option value="C">C:\</option>
					 <option value="D">D:\</option>
					 <option value="E">E:\</option>
					 <option value="F">F:\</option>
					 <option value="G">G:\</option>
					 <option value="H">H:\</option>
				   </select></td>
				
           </tr>
		   
		   
		  
		<tr >
			<td width="100%" align="center"> <INPUT TYPE="button" VALUE="PROSES BACK-UP" style="height:40px;width:120px" onclick="rekal()" >
			</td>
		</tr>
		<tr height="70%" >
			<td align="center" style="visibility:hidden" >	<DIV id="load" > <IMG SRC="<?php echo base_url(); ?>assets/images/loading.gif" WIDTH="270" HEIGHT="40" BORDER="0" ALT=""></DIV></td>
		</tr>
        </table>                      
		
<h3>PROSES RESTORE</h3>
 <table id="sp2d" title="Rekal Transaksi" style="width:870px;height:300px;" > 
		 <center><b>Harap Menggunakan Mozila Firefox untuk merestore data! </b></center>
		<tr>
                <td><b> &nbsp;&nbsp;&nbsp; Nama Database : </b> &nbsp;&nbsp;&nbsp;&nbsp; <input type="text" id="database_restore"   name="database_restore" style="width:250px;" value="simakdaskpd_2016"/></td>
				
           </tr>
		   <tr>
				<td><b>&nbsp;&nbsp;&nbsp; Pilih Directory : </b><select name="dir_restore" id="dir_restore" >
					 <option value="">PILIH DIRECTORY</option>
					 <option value="C">C:\</option>
					 <option value="D">D:\</option>
					 <option value="E">E:\</option>
					 <option value="F">F:\</option>
					 <option value="G">G:\</option>
					 <option value="H">H:\</option>
				   </select></td>
				
           </tr>
		   
		   
		   <td colspan="2"><!-- <input type="text" id="gambar1" name="gambar1" value="IMG_7580.JPG"  style="width:200px;" disabled="disabled" />-->
                                <input type="file" id="fileToUpload1" name="fileToUpload"  webkitdirectory directory multiple />
                                
		<tr >
			<td width="100%" align="center"> <INPUT TYPE="button" VALUE="PROSES RESTORE" style="height:40px;width:120px" onclick="rekal_restore()" >
			</td>
		</tr>
		<tr height="70%" >
			<td align="center" style="visibility:hidden" >	<DIV id="load_restore" > <IMG SRC="<?php echo base_url(); ?>assets/images/loading.gif" WIDTH="270" HEIGHT="40" BORDER="0" ALT=""></DIV></td>
		</tr>
        </table> 		
    </p> 
    </div>
</div>
</div>

 	
</body>

</html>