<div class="row">
    <div class="col-sm">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo $page; ?></h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table" width="100%" cellspacing="0" >
                        <thead>
                            <tr>
                                <th>Nama Bahan</th>
                                <th>Quantity</th>
                                <th>Changed Date</th>
                                <th width="20%">Action</th>
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

$(document).ready(function() {

    // Load data for the table's content from an Ajax source
    table = $('#table').DataTable({ 
        "processing": true,
        "serverSide": true,
        "order": [],

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('Stok/ajax_list')?>",
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
    $(".input-group").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });



});

function reload_table()
{
    table.ajax.reload(null,false); 
}


function exchange(id_stok)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.input-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('Stok/ajax_exchange')?>/" + id_stok,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_bahan"]').val(data.get.id_bahan);
            $('[name="id_unit"]').val(data.get.id_unit);
            $('[name="nama_bahan"]').val(data.get.nama_bahan);
            $('[name="unitid"]').val(data.get.unitid);
            $('[name="jumlah_bahan"]').val(data.get.jumlah_bahan);

            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Exchange'); // Set title to Bootstrap modal title

                var value = data.unit;
                console.log(value);
                $("#unitid_exc").empty();
                if (value.length > 0) {
                    var dataSelectUnit = [];
                    for (let index = 0; index < value.length; index++) {
                        var option = '<option value="'+value[index]['id_unit']+'">'+value[index]['unitid']+'</option>'
                        dataSelectUnit.push(option);
                    }
                    $("#unitid_exc").append('<option value="" selected="selected">Pilih Unit</option>'+dataSelectUnit);
                }


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });

}

function save()
{
    $('#btnSave').text('Saving...'); 
    $('#btnSave').attr('disabled',true);  
    var url;
    url = "<?php echo site_url('Stok/exchange')?>";
    
    // ajax adding data to database
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
            $('#btnSave').text('Save'); 
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

function detail(id) {
    window.location = "<?php echo base_url(); ?>index.php/Stok/dtl/"+id;
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
                    <input name="id_bahan" class="form-control" type="hidden">
                    <input name="id_unit" class="form-control" type="hidden">
                    <input type="hidden" value="" name="id_stok"/> 
                    <div class="form-body">
                        
                        <div class="row">
                            <div class="col-md-4">
                            <!-- <div class="form-group"> -->
                                  
                                <label>Bahan : </label>
                                <input name="nama_bahan" placeholder="jumlah_bahan" class="form-control " type="text" readonly>
                                <span class="help-block"></span>
                                <!-- </div> -->
                            </div>

                            <div class="col-md-4">
                            <!-- <div class="form-group"> -->
                                  
                                <label>Stok Tersedia : </label>
                                <input name="jumlah_bahan" placeholder="jumlah_bahan" class="form-control " type="text" readonly>
                                <span class="help-block"></span>
                                <!-- </div> -->
                            </div>

                            <div class="col-md-4">
                            <!-- <div class="form-group"> -->
                                  
                                <label>Unit : </label>
                                <input name="unitid" placeholder="jumlah_bahan" class="form-control " type="text" readonly>
                                <span class="help-block"></span>
                                <!-- </div> -->
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <!-- <span class="input-group-addon"><i class="fa fa-user-o"></i></span> -->    
                                    <input type="number" class="form-control" name="qty_exc" id="qty_exc" placeholder="From Quantity" required>
                                    <div class="input-group-addon"></div>
                                    <input type="text" class="form-control" name="unitid" readonly>
                                    <div class="input-group-addon ">to</div>
                                    <select name="unitid_exc" id="unitid_exc" class="form-control" required>
                                        <option value="" selected="selected">Pilih Unit</option>
                                    </select>
                                    <!-- <span class="help-block"></span> -->
                                </div>
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