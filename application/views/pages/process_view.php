<div class="col-sm">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo $page; ?></h6>
            </div>

            <div class="card-body">
                <span id="success-msg"></span>
                <!-- <form class="triple_from" id="triple_from"> -->
                <form action="<?php echo site_url('Triple_exp_smoothing/index/1')?>" method="post">
                    <div class="form-group">
                        <div class="input-group input-daterange">
                            <!-- <span class="input-group-addon"><i class="fa fa-user-o"></i></span> -->
                            <input type="text" class="form-control input-contact-datefrom" name="datefrom" id="datefrom" placeholder="From Period" required>
                            <div class="input-group-addon">to</div>
                            <input type="text" class="form-control input-contact-dateto" name="dateto" id="dateto" placeholder="To Period" required>
                            <!-- <span id="dateto_error" class="text-danger"></span> -->
                        </div>
                        
                    </div>
                    <div class ="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <select  name="bahan" class="form-control input-contact-bahan" id="bahan" required>
                                    <option value="" selected="selected">Pilih Bahan : </option>
                                    <?php foreach($bahan as $row){?>
                                        <option value="<?php echo $row->id_bahan;?>"><?php echo $row->nama_bahan; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <select name="unit" id="unit" class="form-control input-contact-unit" required>
                                    <option value="" selected="selected">Pilih Unit : </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" name="alpha" id="alpha" class="form-control input-contact-alpha" placeholder="Alpha : 0 - 1" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <input  type="submit" name="process" id="process" class="btn btn-primary" value="Process">
                    </div>
                </form>
            </div>
        </div>
</div>

<div class="row">
    <div class="col-sm-7">
        <!-- <div class="col-sm"> -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dataset</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table" width="100%" cellspacing="0" >
                            <thead>
                                <tr>
                                    <th width="10%">Periode</th>
                                    <th width="15%">Bahan</th>
                                    <th width="5%">End Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dataset as $d){ ?>
                                    <tr>
                                        <?php $date=date_create($d->tanggal_trans); ?>
                                        <td><?php echo date_format($date,"F Y");?></td>
                                        <td><?php echo $d->nama_bahan;?></td>
                                        <td><?php echo $d->end_qty.' '.$d->unitid;?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <!-- </div> -->
    </div>

    
</div>

<div class="row">
    <div class="col-sm-12">
        <!-- <div class="col-sm"> -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hasil</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table2" class="table data-list-view">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>S`</th>
                                    <th>S``</th>
                                    <th>S```</th>
                                    <th>Ft+m</th>
                                    <th>Aktual</th>
                                    <th>PE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;?>
                                <?php for ($i=0; $i < count($hasil); $i++) { ?>
                                    <tr>
                                        <td><?php echo $no++;?></td>
                                        <td><?php echo $hasil[$i]['s'];?></td>
                                        <td><?php echo $hasil[$i]['ss'];?></td>
                                        <td><?php echo $hasil[$i]['sss'];?></td>
                                        <td><?php echo $hasil[$i]['ft'];?></td>
                                        <td><?php echo $hasil[$i]['actual'];?></td>
                                        <td><?php echo $hasil[$i]['pe'];?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <!-- </div> -->
    </div>
</div>

<script src="<?php echo base_url('assets/asetku/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/asetku/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/asetku/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.25.0/moment.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script> -->

<script type="text/javascript">
    
var save_method; 
var table;
var table2;
var base_url = '<?php echo base_url();?>';


// console.log();
$(document).ready(function() {

    table = $('#table').DataTable({ 
        // ajax: "<!?php echo site_url('Triple_exp_smoothing/get_dataset/')?>"+dataset,
        responsive: true,
    });

    //datepicker
    $('.input-daterange input').each(function() {
    //     // $(this).datepicker('clearDates');
        $(this).datepicker({
            autoclose: true,
            format: "yyyy-mm",
            startView: "months", 
            minViewMode: "months"
        });
    });
    // $('#datetimepicker9').datetimepicker();

    $("#bahan").on('change', function(){
        var id = $("#bahan").val()
        console.log(id);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Triple_exp_smoothing/get_unit')?>",
            data: {id:id},
            success: function(data) {
                var value = JSON.parse(data)
                console.log(value)
                $("#unit").empty();
                if (value.length > 0) {
                    var dataid_unit = [];
                    for (let index = 0; index < value.length; index++) {
                        var option = '<option value="'+value[index]['id_unit']+'">'+value[index]['unitid']+'</option>'
                        dataid_unit.push(option);
                    }
                    $("#unit").append('<option value="" selected="selected">Pilih Unit : </option>'+dataid_unit);
                }
            }
        })
    })

    
});


</script>