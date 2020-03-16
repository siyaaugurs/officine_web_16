<!--Import Export common modal script start -->
<div class="modal" id="import_export_common_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Services Details</h4>
                <hr />
            </div>
            <div class="card-body">
                <a id="export_btn" href="javascript::void()" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export  Services </a>
                <hr />
                <h3 style="font-weight:600;">Import Excel Files </h3> 
                <form id="import_form" >
                @csrf
                  <span id="import_response"></span>
                  <div class="control-group" id="fields">
                        <label class="control-label" for="field1">
                            Browse Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input type="hidden" name="service_id" value="" id="service_id" />
                                <input class="btn btn-primary" name="import_file" type="file"  accept=".csv" required>
                                <span class="input-group-btn">
                        &nbsp;&nbsp;
                        <button class="btn btn-success btn-add" type="submit" id="import_services">
                        Import
                        <span class="glyphicon glyphicon-import"></span>
                                </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
<!--End-->
<!--Import Export car washing details-->
<div class="modal" id="import_export_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Import / Export Car Revision Services</h4>
                <hr />
            </div>
            <div class="card-body">
                <a href="<?php echo url('export/car_reviosion') ?>" class="btn btn-warning"><i class="fa fa-download"></i>&nbsp;Export Car Revision Details</a>
                <hr />
                <h3 style="font-weight:600;">Import Excel Files </h3> 
                <form id="import_car_revision_file_form" >
                @csrf
                  <span id="car_revision_msg_response"></span>
                  <div class="control-group" id="fields">
                        <label class="control-label" for="field1">
                            Browse Files
                        </label>
                        <div class="controls">
                            <div class="entry input-group col-xs-3">
                                <input class="btn btn-primary" name="car_revision_file" type="file"  accept=".csv" required>
                                <span class="input-group-btn">
                        &nbsp;&nbsp;
                        <button class="btn btn-success btn-add" type="submit" id="import_car_revision">
                        Import  Car Revision Details
                        <span class="glyphicon glyphicon-import"></span>
                                </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
<!--End-->