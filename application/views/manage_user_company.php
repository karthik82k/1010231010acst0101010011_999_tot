<?php $this->load->view('include/header_grid'); ?>

  
    <div id="adcontent">
       <table id="flex1"></table>
    <script type="text/javascript">
    $(document).ready(function() {
        $("#flex1").fadeIn('slow');
    });
    $("#flex1").flexigrid({
        url: "<?php echo site_url('/master/user_list'); ?>",
        //  url: '',
        dataType: 'json',
        colModel: [
            {display: 'First Name', name: 'name', width: 135, sortable: true, align: 'left',resizable: false},
            {display: 'Middle Name', name: 'middle_name', width: 30, sortable: true, align: 'left',resizable: true},
            {display: 'Last Name', name: 'last_name', width: 30, sortable: true, align: 'left'},
            {display: 'Display Name', name: 'dispaly_name', width: 90, sortable: true, align: 'left'},
            {display: 'Mobile No', name: 'mobile_no', width: 90, sortable: false, align: 'left'},
            {display: 'Email', name: 'email', width: 110, sortable: false, align: 'left'},
            {display: 'Username', name: 'username', width: 70, sortable: true, align: 'center'},
            {display: 'Role', name: 'role', width: 70, sortable: true, align: 'center'},
            {display: 'Details', name: 'action', width: 60, sortable: false, align: 'center'}
        ],
        buttons: [{name: 'Add', bclass: 'add', onpress: action, attribute: {title: "add", alt: "add", disable: true, style: {'border': '1px solid black;'}}}
        ],
        sortname: "name",
        sortorder: "ASC",
        usepager: true,
        title: "USER LIST",
        useRp: true,
        rp: 15,
        showTableToggleBtn: false,
        width: 990,
    resizable: false,   
        height: 'auto'
    });

    function action(com, grid) {
        if (com == 'Add') {
            window.location.href = '<?php echo base_url('/admin/create_user/'); ?>';
        }
        return false;
    }
    </script>
      </div>
   <?php $this->load->view('include/footer'); ?>
