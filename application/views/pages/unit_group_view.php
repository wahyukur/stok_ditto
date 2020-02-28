<!-- <div class="container"> -->
  <div class="row">
    <div class="col-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo $page; ?></h6>
            </div>
        
            <button onclick="bulk_delete()" class="btn btn-danger btn-icon-split"><span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                <span class="text">HAPUS PILIHAN</span>
            </button>

            <button onclick="add_unit_group()" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="glyphicon glyphicon-plus"></i></span>
                <span class="text">TAMBAH</span>
            </button>
                
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table" width="100%" cellspacing="0" >
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all"></th>
                                <th>Unit Group ID</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th style="width:230px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-5">
        <div class="card shadow mb-4">
        <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo $foreign_key; ?></h6>
            </div>
        
            <button onclick="bulk_delete_unit()" class="btn btn-danger btn-icon-split"><span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                <span class="text">HAPUS PILIHAN</span>
            </button>

            <button onclick="add_unit()" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="glyphicon glyphicon-plus"></i></span>
                <span class="text">TAMBAH</span>
            </button>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table2 table-striped" id="table2" width="100%" cellspacing="0" >
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all-unit"></th>
                                <th>Unit ID</th>
                                <!-- <th>Unit Group ID</th> -->
                                <th>Convertion</th>
                                <th style="width:150px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  </div>
<!-- </div> -->


<script src="<?php echo base_url('assets/asetku/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/asetku/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/asetku/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


<script type="text/javascript">

var save_method; 
var table;
var table2;
var base_url = '<?php echo base_url();?>';
var foreign_key = '<?php echo $foreign_key; ?>';

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true,
        "serverSide": true,
        "order": [],

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('unit_group/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { 
                "targets": [ 0 ], //first column
                "orderable": false, //set not orderable
            },
            { 
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },

        ],

    });

    table2 = $('#table2').DataTable({ 

        "processing": true,
        "serverSide": true,
        "order": [], 

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('unit_group/ajax_list_unit/')?>"+foreign_key,
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { 
                "targets": [ 0 ], //first column
                "orderable": false, //set not orderable
            },
            { 
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },

        ],

    });


    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "bottom auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });


    //check all
    $("#check-all").click(function () {
        $(".data-check").prop('checked', $(this).prop('checked'));
    });

    $("#check-all-unit").click(function () {
        $(".data-check-unit").prop('checked', $(this).prop('checked'));
    });
});



function add_unit_group()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Unit Group'); // Set Title to Bootstrap modal title

}

function edit_unit_group(unit_groupid)
{
    save_method = 'update';
    $('#form_edit')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('unit_group/ajax_edit')?>/" + unit_groupid,
        type: "GET",
        dataType: "JSON",   
        success: function(data)
        {

            $('[name="unit_groupid"]').val(data.unit_groupid);
            $('[name="description"]').val(data.description);
            $('[name="created_at"]').val(data.created_at);
            $('#modal_edit').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Unit Group'); // Set title to Bootstrap modal title


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); 
}

function save()
{
    $('#btnSave').text('saving...'); 
    $('#btnSave').attr('disabled',true);  
    var url;
    url = "<?php echo site_url('unit_group/ajax_add')?>";
    
    var formData = new FormData($('#form')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status)
            {
                $('#modal_form').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); 
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); 
                }
            }
            $('#btnSave').text('save'); 
            $('#btnSave').attr('disabled',false); 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); 
            $('#btnSave').attr('disabled',false); 

        }
    });
}

function update_()
{
    $('#btnUpdate').text('updating...'); 
    $('#btnUpdate').attr('disabled',true);  
    var url;
    url = "<?php echo site_url('unit_group/ajax_update')?>";
    
    // ajax adding data to database
    var formData = new FormData($('#form_edit')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status)
            {
                $('#modal_edit').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); 
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); 
                }
            }
            $('#btnUpdate').text('save'); 
            $('#btnUpdate').attr('disabled',false); 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error update data');
            $('#btnUpdate').text('save'); 
            $('#btnUpdate').attr('disabled',false); 

        }
    });
}

function delete_unit_group(unit_groupid)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('unit_group/ajax_delete')?>/"+unit_groupid,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}



function bulk_delete()
{
    var list_id = [];
    $(".data-check:checked").each(function() {
            list_id.push(this.value);
    });
    if(list_id.length > 0)
    {
        if(confirm('Are you sure delete this '+list_id.length+' data?'))
        {
            $.ajax({
                type: "POST",
                data: {unit_groupid:list_id},
                url: "<?php echo site_url('unit_group/ajax_bulk_delete')?>",
                dataType: "JSON",
                success: function(data)
                {
                    if(data.status)
                    {
                        reload_table();
                    }
                    else
                    {
                        alert('Failed.');
                    }
                    
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }
    else
    {
        alert('no data selected');
    }
}

function detail(unit_groupid) {
    window.location = "<?php echo base_url(); ?>index.php/unit_group/index/"+unit_groupid;
}

//  -------------------------------------------------- Unit ----------------------------------------------------//

function add_unit()
{
    save_method = 'add';
    $('#form_unit')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block-unit').empty(); // clear error string
    $('#modal_form_unit').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Unit'); // Set Title to Bootstrap modal title

}

function edit_unit(id_unit)
{
    save_method = 'update';
    $('#form_edit_unit')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block-unit').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('unit_group/ajax_edit_unit')?>/" + id_unit,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_unit"]').val(data.id_unit);
            $('[name="unitid"]').val(data.unitid);
            $('[name="unit_groupid"]').val(data.unit_groupid);
            $('[name="convertion"]').val(data.convertion);
            $('#modal_edit_unit').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Unit'); // Set title to Bootstrap modal title


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table2()
{
    table2.ajax.reload(null,false); 
}

function save_unit()
{
    $('#btnSaveUnit').text('saving...'); 
    $('#btnSaveUnit').attr('disabled',true);  
    var url;
    url = "<?php echo site_url('unit_group/ajax_add_unit')?>";
    
    var formData = new FormData($('#form_unit')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status)
            {
                $('#modal_form_unit').modal('hide');
                reload_table2();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); 
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); 
                }
            }
            $('#btnSaveUnit').text('save'); 
            $('#btnSaveUnit').attr('disabled',false); 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error karena terdapat unitid dengan unitgroup yang sama !');
            $('#btnSaveUnit').text('save'); 
            $('#btnSaveUnit').attr('disabled',false); 

        }
    });
}

function update_unit()
{
    $('#btnUpdate').text('updating...'); 
    $('#btnUpdate').attr('disabled',true);  
    var url;
    url = "<?php echo site_url('unit_group/ajax_update_unit')?>";
    
    // ajax adding data to database
    var formData = new FormData($('#form_edit_unit')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status)
            {
                $('#modal_edit_unit').modal('hide');
                reload_table2();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); 
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); 
                }
            }
            $('#btnUpdate').text('save'); 
            $('#btnUpdate').attr('disabled',false); 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnUpdate').text('save'); 
            $('#btnUpdate').attr('disabled',false); 

        }
    });
}

function delete_unit(id_unit)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('unit_group/ajax_delete_unit')?>/"+id_unit,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form_unit').modal('hide');
                reload_table2();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}



function bulk_delete_unit()
{
    var list_id = [];
    $(".data-check-unit:checked").each(function() {
            list_id.push(this.value);
    });
    if(list_id.length > 0)
    {
        if(confirm('Are you sure delete this '+list_id.length+' data?'))
        {
            $.ajax({
                type: "POST",
                data: {id_unit:list_id},
                url: "<?php echo site_url('unit_group/ajax_bulk_delete_unit')?>",
                dataType: "JSON",
                success: function(data)
                {
                    if(data.status)
                    {
                        reload_table2();
                    }
                    else
                    {
                        alert('Failed.');
                    }
                    
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }
    else
    {
        alert('no data selected');
    }
}

</script>

<!-- Bootstrap modal Unit Grroup -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit Group ID</label>
                            <div class="col-md-9">
                                <input name="unit_groupid" placeholder="cth : Galon / Kopi25L" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Description</label>
                            <div class="col-md-9">
                                <input name="description" placeholder="cth : Galon Aqua / Kopi Kemasan 25Liter" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="control-label col-md-3">Created_at</label>
                            <div class="col-md-9">
                                <input name="created_at" placeholder="created_at" class="form-control datepicker" type="datepicker">
                                <span class="help-block"></span>
                            </div>
                        </div> -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap modal Edit Unit Group -->
<div class="modal fade" id="modal_edit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_edit" class="form-horizontal">
                    <!-- <input type="hidden" value="" name="id_unit"/>  -->
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit Group ID</label>
                            <div class="col-md-9">
                                <input name="unit_groupid" placeholder="ID unit" class="form-control" type="text" readonly >
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Description</label>
                            <div class="col-md-9">
                                <input name="description" placeholder="description" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Created At</label>
                            <div class="col-md-9">
                                <input name="created_at" placeholder="created_at" class="form-control" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnUpdate" onclick="update_()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap modal Unit Convertion -->
<div class="modal fade" id="modal_form_unit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_unit" class="form-horizontal">
                    <input type="hidden" value="" name="id_unit"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit</label>
                            <div class="col-md-9">
                                <input name="unitid" placeholder="cth : sendok / liter / pcs / etc" class="form-control " type="text">
                                <span class="help-block-unit"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit Group</label>
                            <div class="col-md-9">         
                                <input name="unit_groupid" value="<?php echo $foreign_key; ?>" class="form-control " type="text" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Convertion</label>
                            <div class="col-md-9">
                                <input name="convertion" placeholder="" class="form-control " type="number">
                                <span class="help-block-unit"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSaveUnit" onclick="save_unit()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap modal Edit Unit Convertion -->
<div class="modal fade" id="modal_edit_unit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_edit_unit" class="form-horizontal">
                    <input type="hidden" value="" name="id_unit"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit</label>
                            <div class="col-md-9">
                                <input name="unitid" placeholder="cth : sendok / liter / pcs / etc" class="form-control" type="text">
                                <span class="help-block-unit"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit Group</label>
                            <div class="col-md-9">
                                <input name="unit_groupid" placeholder="unit_groupid" class="form-control " type="text" readonly>
                                <span class="help-block-unit"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Convertion</label>
                            <div class="col-md-9">
                                <input name="convertion" placeholder="convertion" class="form-control " type="number">
                                <span class="help-block-unit"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnUpdateUnit" onclick="update_unit()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>