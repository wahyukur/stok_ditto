<style>
    .tulisan_apik{
        text-align: center;
        font-family:Impact;
        font-size:150%;
    }

    .tulisan_tanggal{
        text-align: center;
        font-family:courier;
        font-size:150%;
    }
</style>

<div class="row">
    <div class="col-3">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Header</h6>
            </div>
            <div class="card-body">
                <div class = "row">
                    <div class="col-12">
                        <p class="tulisan_apik"> <?php echo $id_masuk; ?></p>
                        <p class="tulisan_tanggal"> <?php echo date('d F Y', strtotime($tgl_masuk)); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-9">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail</h6>
            </div>

            <button onclick="bulk_delete()" class="btn btn-danger btn-icon-split"><span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                <span class="text">HAPUS PILIHAN</span>
            </button>

            <button onclick="add_detail()" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="glyphicon glyphicon-plus"></i></span>
                <span class="text">TAMBAH</span>
            </button>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table" width="100%" cellspacing="0" >
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nama Bahan</th>
                                <th>Qty</th>
                                <th>Unit Satuan</th>
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

<script src="<?php echo base_url('assets/asetku/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/asetku/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/asetku/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


<script type="text/javascript">

var save_method; 
var table;
var base_url = '<?php echo base_url();?>';
var id_masuk = '<?php echo $id_masuk; ?>';

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true,
        "serverSide": true,
        "order": [],

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('laporan_masuk/ajax_list_detail/')?>"+id_masuk,
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

    $("#id_bahan").on('change', function(){
        var id = $("#id_bahan").val()
        console.log(id);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('laporan_masuk/get_ug')?>",
            data: {id:id},
            success: function(data) {
                var value = JSON.parse(data)
                console.log(value)
                $("#selectUnit").empty();
                if (value.length > 0) {
                    var dataSelectUnit = [];
                    for (let index = 0; index < value.length; index++) {
                        var option = '<option value="'+value[index]['id_unit']+'">'+value[index]['unitid']+'</option>'
                        dataSelectUnit.push(option);
                    }
                    $("#selectUnit").append('<option value="" selected="selected">Pilih Unit</option>'+dataSelectUnit);
                }
            }
        })
    
    })

    $("#bahan").on('change', function(){
        var id = $("#bahan").val()
        console.log(id);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('laporan_masuk/get_ug')?>",
            data: {id:id},
            success: function(data) {
                var value = JSON.parse(data)
                console.log(value)
                $("#unitselected").empty();
                if (value.length > 0) {
                    var dataSelectUnit = [];
                    for (let index = 0; index < value.length; index++) {
                        var option = '<option value="'+value[index]['id_unit']+'">'+value[index]['unitid']+'</option>'
                        dataSelectUnit.push(option);
                    }
                    $("#unitselected").append('<option value="" selected="selected">Pilih Unit</option>'+dataSelectUnit);
                }
            }
        })

    })

});



function add_detail()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Detail'); // Set Title to Bootstrap modal title

}

function edit_detail(id)
{
    save_method = 'update';
    $('#form_edit')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('laporan_masuk/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            
            console.log(data);

            $('[name="id"]').val(data.id);
            $('[name="id_masuk"]').val(data.id_masuk);
            $('[name="id_bahan"]').val(data.id_bahan);
            $('[name="jumlah_bahan"]').val(data.qty);
            $('[name="unitid"]').val(data.unitid);
            $('#modal_edit').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Detail'); // Set title to Bootstrap modal title


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
    url = "<?php echo site_url('laporan_masuk/ajax_add_detail')?>";
    
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
    url = "<?php echo site_url('laporan_masuk/ajax_update')?>";
    
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

function delete_detail(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('laporan_masuk/ajax_delete')?>/"+id,
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
                data: {id_laporan_masuk:list_id},
                url: "<!?php echo site_url('laporan_masuk/ajax_bulk_delete')?>",
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
                    <input type="hidden" value="<?php echo $id_masuk; ?>" name="id_masuk"/> 
                    <div class="form-group">
                        <label class="control-label col-md-3">Bahan</label>
                        <div class="col-md-9">
                            <!-- <input name="id_bahan" placeholder="ID bahan" class="form-control " type="text">
                            <span class="help-block"></span> -->
                            <select  name="id_bahan" class="form-control" id="id_bahan">
                                <option value="" selected="selected">Pilih Bahan</option>
                                <?php foreach($bahans as $row){?>
                                    <option value="<?php echo $row->id_bahan;?>"><?php echo $row->nama_bahan;?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Qty</label>
                        <div class="col-md-9">
                            <input name="jumlah_bahan" class="form-control " type="number">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Unit</label>
                        <div class="col-md-9">
                            <select name="unitid" id="selectUnit" class="form-control">
                                <option value="" selected="selected">Pilih Unit</option>
                            </select>
                            <span class="help-block"></span>
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
                    <input type="hidden" name="id"/>
                    <input type="hidden" name="id_masuk"/>
                    <div class="form-body">
                    <div class="form-group">
                        <label class="control-label col-md-3">Bahan</label>
                        <div class="col-md-9">
                            <!-- <input name="id_bahan" placeholder="ID bahan" class="form-control " type="text">
                            <span class="help-block"></span> -->
                                <select  name="id_bahan" class="form-control" id="bahan">
                                    <option value="" selected="selected">Pilih Bahan</option>
                                    <?php foreach($bahans as $row){?>
                                        <option value="<?php echo $row->id_bahan;?>"><?php echo $row->nama_bahan;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Qty</label>
                            <div class="col-md-9">
                                <input name="jumlah_bahan" class="form-control " type="number">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Unit</label>
                            <div class="col-md-9">
                                <select name="unitid" id="unitselected" class="form-control">
                                    <option value="" selected="selected">Pilih Unit</option>
                                </select>
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