<?php include 'header.php' ?>
<?php if(!check_permission('material-issue-add')){ 
        include("404.php");
        exit();
 } ?>
<!-- Left Sidebar End -->

<div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Issue Entry</li>
    </ol>
    <!-- DataTables Example -->
    <div class="card mb-3">
        <div class="card-header">
            <i class="fas fa-table"></i>
            Warehouse to Warehouse transfer Entry Form</div>
        <div class="card-body">
            <!--here your code will go-->
            <div class="form-group">
                <form action="" method="post" name="add_name" id="transfer_entry_form" onsubmit="showFormIsProcessing('transfer_entry_form');">
                    <div class="row" id="div1" style="">
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label>Transfer Date</label>
                                <input type="text" autocomplete="off" name="transfer_date" id="transfer_date" class="form-control datepicker" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label>Transfer No</label>
                                <?php if($_SESSION['logged']['user_type'] == 'whm')
                                    {
                                        $warehouse_id   =   $_SESSION['logged']['warehouse_id'];
                                        $sql    =   "SELECT * FROM inv_warehosueinfo WHERE `id`='$warehouse_id'";
                                        $result = mysqli_query($conn, $sql);
                                        $row=mysqli_fetch_array($result);
                                        $short_name = $row['short_name'];
                                        $transferCode= 'WT-'.$short_name;
                                    } else{
                                        $transferCode= 'WT-CW';
                                    }
                                ?>
                                <input type="text" name="transfer_id" id="transfer_id" class="form-control" readonly="readonly" value="<?php echo getDefaultCategoryCodeByWarehouseT('inv_transfermaster', 'transfer_id', '03d', '001', $transferCode) ?>">
                                <input type="hidden" name="receive_no" id="receive_no" value="<?php echo getDefaultCategoryCodeByWarehouseT('inv_transfermaster', 'transfer_id', '03d', '001', $transferCode) ?>">
                            </div>
                        </div>
                        
                        
                        <?php if($_SESSION['logged']['user_type'] == 'whm') { ?>
                        <div class="col-xs-3">
                            <div class="form-group">
                                    <label>From Warehouse</label>
                                    
                                    <?php  
                                        $warehouse_id = $_SESSION['logged']['warehouse_id'];                                
                                        $dataresult =   getDataRowByTableAndId('inv_warehosueinfo', $warehouse_id);
                                    ?>
                                    <input type="text" class="form-control" readonly="readonly" value="<?php echo (isset($dataresult) && !empty($dataresult) ? $dataresult->name : ''); ?>">
                                    
                                    <input type="hidden" name="from_warehouse" id="from_warehouse" class="form-control" readonly="readonly" value="<?php echo $_SESSION['logged']['warehouse_id']; ?>">
                                    
                            </div>
                        </div>
                        <?php }else { ?>
                        
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="id">From Warehouse </label>
                                <select class="form-control" id="from_warehouse" name="from_warehouse" required>
                                    <option value="">Select</option>
                                    <?php
                                    $projectsData = getTableDataByTableName('inv_warehosueinfo');

                                    if (isset($projectsData) && !empty($projectsData)) {
                                        foreach ($projectsData as $data) {
                                            ?>
                                            <option value="<?php echo $data['id']; ?>"><?php echo $data['name']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        
                        
                        
                        
                        <div class="col-xs-3">
                            <div class="form-group">
                                <label for="id">To Warehouse </label>
                                <select class="form-control" id="to_warehouse" name="to_warehouse" required>
                                    <option value="">Select</option>
                                    <?php
                                    $projectsData = getTableDataByTableName('inv_warehosueinfo');

                                    if (isset($projectsData) && !empty($projectsData)) {
                                        foreach ($projectsData as $data) {
                                            ?>
                                            <option value="<?php echo $data['id']; ?>"><?php echo $data['name']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>





                    </div>
                    <div class="row" id="div1"  style="">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dynamic_field">
                                <thead>
                                <th width="20%">Material Name<span class="reqfield"> ***required</span></th>
                                <th width="15%">Receive Lot</th>
                                <th width="10%">Unit</th>
                                <th width="10%">In Stock</th>
                                <th width="10%">Unit Price</th>
                                <th width="10%">Qty<span class="reqfield"> ***required</span></th>
                                <th width="10%">Amount</th>
                                <th width="5%"></th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control material_select_2" id="material_name" name="material_name[]" required onchange="getItemCodeByParam(this.value, 'inv_material', 'material_id_code', 'material_id0', 'qty_unit',$(this));">
                                                <option value="">Select</option>
                                                <?php
												$graterThanZero=1;
                                                $projectsData = get_product_with_category($graterThanZero);
                                                if (isset($projectsData) && !empty($projectsData)) {
                                                    foreach ($projectsData as $data) {
                                                        ?>
                                                        <option value="<?php echo $data['id']; ?>"><?php echo $data['material_name']; ?> - <?php echo $data['part_no']; ?> - <?php echo $data['spec']; ?> - <?php echo $data['item_code']; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
										<input type="hidden" name="material_id[]" id="material_id0" class="form-control" required readonly>
										<select class="form-control product_price_id material_select_2" id="product_price_id0" name="product_price_id[]">
											<option></option>
										</select>
										</td>
                                        <td>
                                            <select class="form-control" id="unit0" name="unit[]" required readonly>
                                                <option value="">Select Unit</option>
                                                <?php
                                                $projectsData = getTableDataByTableName('inv_item_unit', '', 'unit_name');
                                                if (isset($projectsData) && !empty($projectsData)) {
                                                    foreach ($projectsData as $data) {
                                                        ?>
                                                        <option value="<?php echo $data['id']; ?>"><?php echo $data['unit_name']; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td><input type="text" name="material_total_stock[]" id="material_total_stock0" class="form-control" readonly ></td>
										<td><input type="text" name="unit_price[]" id="unit_price0" class="form-control" readonly ></td>
                                        <td><input type="number" name="quantity[]" id="quantity0" onchange="sum(0)" onkeyup="check_stock_quantity_validation(0)" class="form-control common_issue_quantity" step="any" min="0" required></td>
										<td><input type="text" name="totalamount[]" id="sum0" class="form-control" readonly></td>
										
                                        <td><button type="button" name="add" id="add" class="btn" style="background-color:#007BFF;color:#ffffff;">+</button></td>
                                    </tr>
                                </tbody>
                            </table>
							<table class="table table-bordered">
								<tr>
									<td width="80%" style="text-align:right;">Total Amount</td>
									<td><input type="text" class="form-control" maxlength="30" name="sub_total_amount" id="allsum" readonly /></td>
								</tr>
							</table>
                        </div>
                    </div>
                    <div class="row" style="">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label>Remarks</label>
                                <textarea id="remarks" name="remarks" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="modal-footer">
                                    <input type="submit" name="transfer_submit" id="submit" class="btn btn-block"  style="background-color:#007BFF;color:#ffffff;" value="SAVE" />
                                </div>    
                            </div>
                        </div>
                    </div>


                </form>
            </div>
            <!--here your code will go-->
        </div>
    </div>
</div>
<!-- /.container-fluid -->
<script>
    var i = 0;
    $(document).ready(function () {
        $('#add').click(function () {    
        var this_row_this = $(this);    
        console.log(this_row_this)    
            i++;
            $('#dynamic_field').append('<tr id="row' + i + '"><td><select class="form-control material_select_2" id="material_name' + i + '" name="material_name[]" required onchange="getAppendItemCodeByParam(' + i + ",'inv_material'," + "'material_id_code'," + "'material_id'," + "'qty_unit'" + ')"><option value="">Select</option><?php
                                    $projectsData = get_product_with_category();
                                    if (isset($projectsData) && !empty($projectsData)) {
                                        foreach ($projectsData as $data) {
                                            ?><option value="<?php echo $data['id']; ?>"><?php echo $data['material_name']; ?> - <?php echo $data['part_no']; ?> - <?php echo $data['spec']; ?></option><?php
                                        }
                                    }
                                    ?></select></td><td><input type="hidden" name="material_id[]" id="material_id' + i + '" class="form-control" required readonly><select class="form-control product_price_id material_select_2" id="product_price_id' + i + '" name="product_price_id[]"><option></option></select></td><td><select class="form-control select2" id="unit' + i + '" name="unit[]' + i + '" required readonly onchange="getAppendItemCodeByParam(' + i + ",'inv_material'" + ",'material_id_code'" + ",'material_id''" + ",'qty_unit'" + ')"><option value="">Select</option><?php
                                    $projectsData = getTableDataByTableName('inv_item_unit', '', 'unit_name');
                                    if (isset($projectsData) && !empty($projectsData)) {
                                        foreach ($projectsData as $data) {
                                            ?><option value="<?php echo $data['id']; ?>"><?php echo $data['unit_name']; ?></option><?php
                                        }
                                    }
                                    ?></select></td><td><input type="text" name="material_total_stock[]" id="material_total_stock' + i + '" class="form-control" readonly></td><td><input type="text" name="unit_price[]" id="unit_price' + i + '" class="form-control" readonly></td><td><input type="number" name="quantity[]" id="quantity' + i + '" onchange="sum(0)"  onkeyup="check_stock_quantity_validation(' + i + ')" class="form-control common_issue_quantity" step="any" min="0" required></td><td><input type="text" name="totalamount[]" id="sum' + i + '" class="form-control" readonly></td><td><button type="button" name="remove" id="' + i + '" class="btn btn_remove" style="background-color:#f26522;color:#ffffff;">X</button></td></tr>');
            
           $(".material_select_2").select2();
												$('#quantity' + i + ', #unit_price' + i).change(function () {
                sum(i)
            });
        });

        $(document).on('click', '.btn_remove', function () {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
            sum_total();
        });
    });


 $(document).ready(function () {
        //this calculates values automatically 
        sum(0);
    });

    function sum(i) {
        var quantity1 = document.getElementById('quantity' + i).value;
        var unit_price1 = document.getElementById('unit_price' + i).value;
        var result = parseFloat(quantity1) * parseFloat(unit_price1);
        if (!isNaN(result)) {
            document.getElementById('sum' + i).value = result;
        }
        sum_total();
    }
    function sum_total() {
        var newTot = 0;
        for (var a = 0; a <= i; a++) {
            aVal = $('#sum' + a);
            if (aVal && aVal.length) {
                newTot += aVal[0].value ? parseFloat(aVal[0].value) : 0;
            }
        }
        document.getElementById('allsum').value = newTot.toFixed(2);
    }
</script>
<script>
    $(function () {
        $("#issue_date").datepicker({
            inline: true,
            dateFormat: "yy-mm-dd",
            yearRange: "-50:+10",
            changeYear: true,
            changeMonth: true
        });
    });
</script>
<script>
    $('input[type="submit"]').prop("disabled", false);
    var a = 0;
//binds to onchange event of your input field
    $('#picture').bind('change', function () {
        if ($('input:submit').attr('disabled', false)) {
            $('input:submit').attr('disabled', true);
        }
        var ext = $('#picture').val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
            $('#error1').slideDown("slow");
            $('#error2').slideUp("slow");
            a = 0;
        } else {
            var picsize = (this.files[0].size);
            if (picsize > 500000) {
                $('#error2').slideDown("slow");
                a = 0;
            } else {
                a = 1;
                $('#error2').slideUp("slow");
            }
            $('#error1').slideUp("slow");
            if (a == 1) {
                $('input:submit').attr('disabled', false);
            }
        }
    });

</script>
<?php include 'footer.php' ?>