<div class="row">
    <div class="col-sm">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo $page; ?></h6>
            </div>
        
            <button onclick="bulk_delete()" class="btn btn-danger btn-icon-split"><span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                <span class="text">HAPUS PILIHAN</span>
            </button>

            <button onclick="add_menu()" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="glyphicon glyphicon-plus"></i></span>
                <span class="text">TAMBAH</span>
            </button>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table" width="100%" cellspacing="0" >
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all"></th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th style="width:250px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm">    
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo $id_menu; ?></h6>
            </div>
        
            <button onclick="bulk_delete_komposisi()" class="btn btn-danger btn-icon-split"><span class="icon text-white-50"><i class="fas fa-trash"></i></span>
                <span class="text">HAPUS PILIHAN</span>
            </button>

            <button onclick="add_komposisi_menu()" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="glyphicon glyphicon-plus"></i></span>
                <span class="text">TAMBAH</span>
            </button>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table2 table-striped" id="table2" width="100%" cellspacing="0" >
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all-komposisi"></th>
                                <th>Bahan</th>
                                <th>Jumlah</th>
                                <th>Unit</th>
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
var id = '<?php echo $id_menu; ?>';

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true,
        "serverSide": true,
        "order": [],

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('menu/ajax_list')?>",
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
            "url": "<?php echo site_url('menu/ajax_list_komposisi')?>/"+id,
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
        orientation: "top auto",
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

    $("#check-all-komposisi").click(function () {
        $(".data-check-komposisi").prop('checked', $(this).prop('checked'));
    });

    $("#id_bahan").on('change', function(){
        var id = $("#id_bahan").val()
        // console.log(id);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('menu/get_ug')?>",
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
            url: "<?php echo site_url('menu/get_ug')?>",
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



function add_menu()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add menu'); // Set Title to Bootstrap modal title

}

function edit_menu(id_menu)
{
    save_method = 'update';
    $('#form_edit')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('menu/ajax_edit')?>/" + id_menu,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_menu"]').val(data.id_menu);
            $('[name="nama_menu"]').val(data.nama_menu);
            $('[name="category"]').val(data.category);
            $('#modal_edit').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit menu'); // Set title to Bootstrap modal title


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
    url = "<?php echo site_url('menu/ajax_add')?>";
    
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
    url = "<?php echo site_url('menu/ajax_update')?>";
    
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

function delete_menu(id_menu)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('menu/ajax_delete')?>/"+id_menu,
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
                data: {id_menu:list_id},
                url: "<?php echo site_url('menu/ajax_bulk_delete')?>",
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

function detail(id) {
    window.location = "<?php echo base_url(); ?>index.php/menu/index/"+id;
}

// -------------------------------------------- KOMPOSISI MENU ----------------------------------------------------//

function add_komposisi_menu()
{
    save_method = 'add';
    $('#form_komposisi')[0].reset(); // reset form on modals
    $('.form-group-komposisi').removeClass('has-error'); // clear error class
    $('.help-block-komposisi').empty(); // clear error string
    $('#modal_form_komposisi').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Komposisi'); // Set Title to Bootstrap modal title

}

function edit_komposisi_menu(id_composition)
{
    save_method = 'update';
    $('#form_edit_komposisi')[0].reset(); // reset form on modals
    $('.form-group-komposisi').removeClass('has-error'); // clear error class
    $('.help-block-komposisi').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('menu/ajax_edit_komposisi')?>/" + id_composition,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_composition"]').val(data.id_composition);
            $('[name="id_menu"]').val(data.id_menu);
            $('[name="id_bahan"]').val(data.id_bahan);
            $('[name="jumlah"]').val(data.jumlah);
            $('[name="unitid"]').val(data.unitid);
            $('#modal_edit_komposisi').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Komposisi'); // Set title to Bootstrap modal title


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

function save_komposisi()
{
    $('#btnSaveKomposisi').text('saving...'); 
    $('#btnSaveKomposisi').attr('disabled',true);  
    var url;
    url = "<?php echo site_url('menu/ajax_add_komposisi')?>";
    
    var formData = new FormData($('#form_komposisi')[0]);
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
                $('#modal_form_komposisi').modal('hide');
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
            $('#btnSaveKomposisi').text('save'); 
            $('#btnSaveKomposisi').attr('disabled',false); 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSaveKomposisi').text('save'); 
            $('#btnSaveKomposisi').attr('disabled',false); 

        }
    });
}

function update_komposisi()
{
    $('#btnUpdateKomposisi').text('updating...'); 
    $('#btnUpdateKomposisi').attr('disabled',true);  
    var url;
    url = "<?php echo site_url('menu/ajax_update_komposisi')?>";
    
    // ajax adding data to database
    var formData = new FormData($('#form_edit_komposisi')[0]);
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
                $('#modal_edit_komposisi').modal('hide');
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
            $('#btnUpdateKomposisi').text('save'); 
            $('#btnUpdateKomposisi').attr('disabled',false); 

        }
    });
}

function delete_komposisi_menu(id_composition)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('menu/ajax_delete_komposisi')?>/"+id_composition,
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



function bulk_delete_komposisi()
{
    var list_id = [];
    $(".data-check-komposisi:checked").each(function() {
        list_id.push(this.value);
    });
    if(list_id.length > 0)
    {
        if(confirm('Are you sure delete this '+list_id.length+' data?'))
        {
            $.ajax({
                type: "POST",
                data: {id_composition:list_id},
                url: "<?php echo site_url('menu/ajax_bulk_delete_komposisi')?>",
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
                    <input type="hidden" value="" name="id_menu"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Menu</label>
                            <div class="col-md-9">
                                <input name="nama_menu" placeholder="Nama Menu" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kategori</label>
                            <div class="col-md-9">
                                <!-- <input name="category" placeholder="Last Name" class="form-control" type="text">
                                <span class="help-block"></span> -->
                                 <select  name="category" class="form-control">                     
                                    <option value="makanan"> Makanan</option>  
                                    <option value="milkshake">Milkshake</option> 
                                    <option value="coffee" > Coffee</option> 
                                    <option value="squash" > Squash</option> 
                                </select>
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
                    <!-- <input type="hidden" value="" name="id_menu"/>  -->
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">ID Menu</label>
                            <div class="col-md-9">
                                <input name="id_menu" placeholder="ID Menu" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Menu</label>
                            <div class="col-md-9">
                                <input name="nama_menu" placeholder="Nama Menu" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kategori</label>
                            <div class="col-md-9">
                                <input name="category" placeholder="Last Name" class="form-control" type="text">
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

<!-- MODAL KOMPOSISI -->

<div class="modal fade" id="modal_form_komposisi" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_komposisi" class="form-horizontal">
                    <input type="hidden" value="" name="id_composition"/>
                    <input name="id_menu" value="<?= $id_menu; ?>" type="hidden"> 
                    <div class="form-body">
                        <div class="form-group-komposisi">
                            <label class="control-label col-md-3">Menu</label>
                            <div class="col-md-9">
                                <input name="nama_menu" class="form-control" value="<?= $nama_menu; ?>" type="text" readonly>
                                <span class="help-block-komposisi"></span>
                            </div>
                        </div>
                        <div class="form-group-komposisi">
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
                        <div class="form-group-komposisi">
                            <label class="control-label col-md-3">Jumlah</label>
                            <div class="col-md-9">
                                <input name="jumlah" placeholder="jumlah" class="form-control " type="number">
                                <span class="help-block-komposisi"></span>
                            </div>
                        </div>
                        <div class="form-group-komposisi">
                            <label class="control-label col-md-3">Unit</label>
                            <div class="col-md-9">
                                <select name="unitid" id="selectUnit" class="form-control">
                                    <option value="" selected="selected">Pilih Unit</option>
                                </select>
                                <span class="help-block-komposisi"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSaveKomposisi" onclick="save_komposisi()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_edit_komposisi" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_edit_komposisi" class="form-horizontal">
                    <input name="id_menu" value="<?= $id_menu; ?>" type="hidden">
                    <input name="id_composition" type="hidden">
                    <div class="form-body">
                        <!-- <div class="form-group">
                            <label class="control-label col-md-3">ID composition</label>
                            <div class="col-md-9">
                                <input name="id_composition" placeholder="ID composition" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div> -->
                        <div class="form-group-komposisi">
                            <label class="control-label col-md-3">Nama Menu</label>
                            <div class="col-md-9">
                                <input name="nama_menu" value="<?= $nama_menu; ?>" class="form-control" type="text" readonly>
                                <span class="help-block-komposisi"></span>
                            </div>
                        </div>

                        <div class="form-group-komposisi">
                            <label class="control-label col-md-3">Bahan</label>
                            <div class="col-md-9">
                                <select  name="id_bahan" class="form-control" id="bahan">
                                    <option value="" selected="selected">Pilih Bahan</option>
                                    <?php foreach($bahans as $row){?>
                                        <option value="<?php echo $row->id_bahan;?>"><?php echo $row->nama_bahan;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-komposisi">
                            <label class="control-label col-md-3">Jumlah</label>
                            <div class="col-md-9">
                                <input name="jumlah" placeholder="jumlah" class="form-control " type="text">
                                <span class="help-block-komposisi"></span>
                            </div>
                        </div>
                        <!-- <div class="form-group-komposisi">
                            <label class="control-label col-md-3">Unit</label>
                            <div class="col-md-9">
                                <input name="unitid" placeholder="Unit" class="form-control " type="text">
                                <span class="help-block"></span>
                            </div>
                        </div> -->
                        <div class="form-group-komposisi">
                            <label class="control-label col-md-3">Unit</label>
                            <div class="col-md-9">
                                <select name="unitid" id="unitselected" class="form-control">
                                    <option value="" selected="selected">Pilih Unit</option>
                                </select>
                                <span class="help-block-komposisi"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnUpdateKomposisi" onclick="update_komposisi()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->