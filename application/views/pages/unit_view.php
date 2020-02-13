<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $page; ?></h1>
    <div class="col-m-2">
        <button class="btn btn-danger" onclick="bulk_delete()"><i class="glyphicon glyphicon-trash"></i> Bulk Delete</button>
        <button class="btn btn-success" onclick="add_unit()"><i class="glyphicon glyphicon-plus"></i> Add Unit</button>
    </div>
    
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table" width="100%" cellspacing="0" >
                <thead>
                    <tr>
                        <th><input type="checkbox" id="check-all"></th>
                        <th>Unit ID</th>
                        <th>Unit Group ID</th>
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


<script src="<?php echo base_url('assets/asetku/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/asetku/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/asetku/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


<script type="text/javascript">

var save_method; 
var table;
var base_url = '<?php echo base_url();?>';
var foreign_key = '<?php echo $foreign_key; ?>';
console.log("<?php echo site_url('unit/ajax_list/')?>"+foreign_key);

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true,
        "serverSide": true,
        "order": [], 

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('unit/ajax_list/')?>"+foreign_key,
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



function add_unit()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Unit'); // Set Title to Bootstrap modal title

}

function edit_unit(id_unit)
{
    save_method = 'update';
    $('#form_edit')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('unit/ajax_edit')?>/" + id_unit,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_unit"]').val(data.id_unit);
            $('[name="unitid"]').val(data.unitid);
            $('[name="unit_groupid"]').val(data.unit_groupid);
            $('[name="convertion"]').val(data.convertion);
            $('#modal_edit').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Unit'); // Set title to Bootstrap modal title


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
    url = "<?php echo site_url('unit/ajax_add')?>";
    
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
            alert('Error karena terdapat unitid dengan unitgroup yang sama !');
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
    url = "<?php echo site_url('unit/ajax_update')?>";
    
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

function delete_unit(id_unit)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('unit/ajax_delete')?>/"+id_unit,
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
                data: {id_unit:list_id},
                url: "<?php echo site_url('unit/ajax_bulk_delete')?>",
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
                    <input type="hidden" value="" name="id_unit"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit</label>
                            <div class="col-md-9">
                                <input name="unitid" placeholder="cth : sendok / liter / pcs / etc" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit Group</label>
                            <div class="col-md-9">
                                 <select  name="unit_groupid" class="form-control">
                                    <?php foreach($ambil_unit as $row){?>                    
                                        <option value="<?php echo $row->unit_groupid;?>"><?php echo $row->unit_groupid;?></option>  
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Convertion</label>
                            <div class="col-md-9">
                                <input name="convertion" placeholder="perbandingan" class="form-control " type="number">
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
                    <input type="hidden" value="" name="id_unit"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit</label>
                            <div class="col-md-9">
                                <input name="unitid" placeholder="cth : sendok / liter / pcs / etc" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit Group</label>
                            <div class="col-md-9">
                                <!-- <input name="unit_groupid" placeholder="unit_groupid" class="form-control " type="text">
                                <span class="help-block"></span> -->
                                <select  name="unit_groupid" class="form-control">
                                    <?php foreach($ambil_unit as $row){?>                    
                                        <option value="<?php echo $row->unit_groupid;?>"><?php echo $row->unit_groupid;?></option>  
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Convertion</label>
                            <div class="col-md-9">
                                <input name="convertion" placeholder="convertion" class="form-control " type="number">
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
            <input type="hidden" name="id_unit" class="form-control">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            <button type="button" onclick="delete_btn()" class="btn btn-primary">Yes</button>
          </div>
        </div>
      </div>
    </div>
    </form> -->
<!--END MODAL DELETE-->