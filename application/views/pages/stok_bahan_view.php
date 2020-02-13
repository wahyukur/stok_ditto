<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $page; ?></h1>
  <!--   <div class="col-m-2">
        <button class="btn btn-danger" onclick="bulk_delete()"><i class="glyphicon glyphicon-trash"></i> Bulk Delete</button>
        <button class="btn btn-success" onclick="add_stok_bahan()"><i class="glyphicon glyphicon-plus"></i> Add stok_bahan</button>
    </div> -->
    
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table" width="100%" cellspacing="0" >
                <thead>
                    <tr>
                        <!-- <th><input type="checkbox" id="check-all"></th> -->
                         <th>Nama Bahan</th>
                         <th>Qty</th>
                         <th>Unit</th>
                         <th>Changed Date</th>
                        <!-- <th style="width:150px;">Action</th> -->
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="<?php echo base_url('assets/asetku/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/asetku/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/asetku/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


<script type="text/javascript">

var save_method; 
var table;
var base_url = '<?php echo base_url();?>';

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true,
        "serverSide": true,
        "order": [],

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('stok_bahan/ajax_list')?>",
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

});



function add_stok_bahan()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add stok_bahan'); // Set Title to Bootstrap modal title

}

function edit_stok_bahan(id_stok)
{
    save_method = 'update';
    $('#form_edit')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('stok_bahan/ajax_edit')?>/" + id_stok,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_stok"]').val(data.id_stok);
            $('[name="id_bahan"]').val(data.id_bahan);
            $('[name="jumlah_bahan"]').val(data.jumlah_bahan);
            $('[name="unit_stok"]').val(data.unit_stok);
            $('[name="changed_date"]').val(data.changed_date);
            $('#modal_edit').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit stok_bahan'); // Set title to Bootstrap modal title


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
    url = "<?php echo site_url('stok_bahan/ajax_add')?>";
    
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
    url = "<?php echo site_url('stok_bahan/ajax_update')?>";
    
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
            alert('Error adding / update data');
            $('#btnUpdate').text('save'); 
            $('#btnUpdate').attr('disabled',false); 

        }
    });
}

function delete_stok_bahan(id_stok)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('stok_bahan/ajax_delete')?>/"+id_stok,
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
                data: {id_stok:list_id},
                url: "<?php echo site_url('stok_bahan/ajax_bulk_delete')?>",
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

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id_stok"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">id_bahan</label>
                            <div class="col-md-9">
                                <input name="id_bahan" placeholder="id_bahan" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">jumlah_stok</label>
                            <div class="col-md-9">
                                <input name="jumlah_bahan" placeholder="jumlah_bahan" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">unit_stok</label>
                            <div class="col-md-9">
                                <input name="unit_stok" placeholder="unit_stok" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">changed_date</label>
                            <div class="col-md-9">
                                <input name="changed_date" placeholder="changed_date" class="form-control datepicker" type="datepicker">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_edit" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_edit" class="form-horizontal">
                    <!-- <input type="hidden" value="" name="id_stok"/>  -->
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">ID stok</label>
                            <div class="col-md-9">
                                <input name="id_stok" placeholder="ID stok" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">id_bahan</label>
                            <div class="col-md-9">
                                <input name="id_bahan" placeholder="id_bahan" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">jumlah_stok</label>
                            <div class="col-md-9">
                                <input name="jumlah_bahan" placeholder="jumlah_bahan" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">unit_stok</label>
                            <div class="col-md-9">
                                <input name="unit_stok" placeholder="unit_stok" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">changed_date</label>
                            <div class="col-md-9">
                                <input name="changed_date" placeholder="changed_date" class="form-control datepicker" type="text">
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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

 <!--MODAL DELETE-->
<!--  <form>
    <div class="modal fade" id="Modal_Delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
               <strong>Are you sure to delete this record?</strong>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="id_stok_bahan" class="form-control">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            <button type="button" onclick="delete_btn()" class="btn btn-primary">Yes</button>
          </div>
        </div>
      </div>
    </div>
    </form> -->
<!--END MODAL DELETE-->