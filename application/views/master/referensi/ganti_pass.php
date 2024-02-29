<script src="<?php echo base_url(); ?>assets/sweetalert/lib/sweet-alert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert/lib/sweet-alert.css">
   <script type="text/javascript">


   //has been modify by demansyah MSM Biak
     function simpan(){
        var id          = '<?php echo ($this->session->userdata('pcUser')); ?>';
        //var uskpd         = '<?php echo ($this->session->userdata('unit_skpd')); ?>';
        var skpd        = '<?php echo ($this->session->userdata('kdskpd')); ?>';
        
        var nm_admin    = document.getElementById('nm_admin').value;
        //var email         = document.getElementById('email').value;
        var password    = document.getElementById('password').value;
        var reply_pass  = document.getElementById('reply_pass').value;
        var waktu       = '<?php echo date('y-m-d H:i:s'); ?>'; 

     

        if(password=='' || reply_pass==''){
            swal({
                    title: "Warning!",
                    text: "PASSWORD Harap Diisi",
                    type: "warning",
                    confirmButtonText: "OK"
                    });
                    exit();
        }




        if(password==reply_pass){
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/master/simpan_password',
                data: ({id:id,skpd:skpd,nm_admin:nm_admin,password:password,reply_pass:reply_pass,waktu:waktu}),
                 success:function(data){
                   status = data.pesan;                    
                   if (status == '0'){
                   swal({
                    title: "Error!",
                    text: "DATA TIDAK TERSIMPAN, MOHON DIULANG.!!",
                    type: "error",
                    confirmButtonText: "OK"
                    });
                    exit();
                   } else {                                 
                    /*swal({
                    title: "Berhasil",
                    text: "DATA TERSIMPAN, SILAHKAN LOGIN KEMBALI..!!",
                    imageUrl:"<?php echo base_url();?>/lib/images/biak.jpg"
                    }); */  

                        swal({
                              title: "USERNAME & PASSWORD Telah Diperbaharui",
                              text: "DATA TERSIMPAN, SILAHKAN LOGIN KEMBALI..!!",
                              type: "warning",
                              //showCancelButton: true,
                              confirmButtonColor: "#DD6B55",
                              confirmButtonText: "Yes"
                            },
                            function(isConfirm){
                                
                              if (isConfirm) {
                                $(document).ready(function(){
                                        $.ajax({url:'<?php echo base_url(); ?>index.php/welcome/logout',
                                                 dataType:'json',
                                                 type: "POST",    
                                                 data:({})
                                                 
                                                });           
                                        });
                                
                              } else {
                                    swal("Cancelled", "Your imaginary file is safe :)", "error");
                              }
                            });                            
                    }                                                                                                         
                }
            });
        });  
        
        }
        else{
                swal({
                    title: "Warning!",
                    text: "MAAF,PASSWORD TIDAK SAMA.!!",
                    type: "warning",
                    confirmButtonText: "OK"
                    });
                    //exit();
        }
    }
    
    function reset(){
     $("#username").attr("value",'');
     $("#password").attr("value",'');
     $("#reply_pass").attr("value",'');
    }

    
  
  
   </script>
<div id="content"> 
    
     <head>
    <title >GANTI PASSWORD</title>
    <style type="text/css">
        #formdaftar {
            border-radius: 8px;
            margin: auto;
            width: 450px;
            align: center;
            /*border: 1px solid #c0c0c0;*/
            font-family: verdana;
            padding: 10px;
        }
        #formdaftar h3 {
            color: #000000;
            padding: 0px 0px 10px 0px;
            margin: 0px;
            font-family: basic title font;
            font-size: 20px;
        }
        #formdaftar p {
            color: #000000;
            margin: 0px;
        }
        .input {
            /*border-radius: 5px;*/
            margin-bottom: 7px;
            width: 450px;
            height: 30px;
        }
        .daftar {
            background-color: #495677;
            color: white;
            font-family: basic title font;
            font-size: 24px;
            width: 150px;
            height: 35px;
            font-weight: bolder;
            border-radius: 5px;
        }
        .daftar:hover {
            color: #efefef;
            background-color: #007cc3;
        }
    </style>
    </head>
<body>
    
        <h3 align="center">GANTI PASSWORD</h3><hr/>
    <table border="0" width="100%">
    <TR>
        <TD ALIGN="left"> 
            <form id="formdaftar" name="formdaftar" >
                
                <p>ID</p>
                <input disabled="true" value="<?php echo ($this->session->userdata('pcUser')); ?>" class="input" type="text">
                <p>SKPD</p>
                <input disabled="true" value="<?php echo ($this->session->userdata('kdskpd')); ?>--<?php echo strtoupper($this->session->userdata('Display_name')); ?>" class="input" type="text">
                <p>NAMA ADMIN</p>
                <input class="input" type="text" id="nm_admin" name="nm_admin" value="<?php echo strtoupper($this->session->userdata('Display_name')); ?>" placeholder="*Isi nama admin pengguna" disabled>
                <!-- <p>E-mail</p>
                <input class="input" type="text" id="email" name="email" placeholder="*E-mail kantor/Admin"> -->
                <p>Password</p>
                <input class="input" type="password" id="password" name="password" placeholder="*Password">
                <p>Confirm Password</p>
                <input class="input" type="password" id="reply_pass" name="reply_pass" placeholder="*Confirm Password"><br><br>
                <button type="submit" onclick="javascript:simpan()"  value="SIMPAN">SIMPAN</button>&nbsp; &nbsp; &nbsp;<button type="edit" onclick="javascript:reset()"  value="RESET">RESET</button>
            </form>
        </TD>
       <TD ALIGN="right"> 
            <img id="loading" src="<?php echo base_url();?>image/password.png"> 
       </TD>
    </TR>
    </table>
    </body>     
    
</div>



