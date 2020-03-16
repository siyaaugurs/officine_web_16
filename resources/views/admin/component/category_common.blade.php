<div class="modal" id="add_car_wash_image_popup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="text-danger fa fa-times"></i></button>
                <h4 class="modal-title" id="myModalLabel">Upload Images</h4>
                <hr />
            </div>
            <div id="err_response"></div>
            <!-- Modal body -->
            <form id="edit_category_image">
                @csrf
                <input type="hidden" name="category_id" id="category_id" value="" readonly="readonly" />
                <div class="modal-body">
                   <div class="control-group" id="fields">
          <label class="control-label" for="field1">
           Browse Multiple Image
          </label>
          <div class="controls">
              <div class="entry input-group col-xs-3">
                <input class="btn btn-primary" name="cat_file_name[]" type="file" multiple="multiple" accept=".jpg,.png," require>
                <span class="input-group-btn">
                 &nbsp;&nbsp;
                 <button class="btn btn-success btn-add" type="submit" id="save_group_image">
                   Save
                   <span class="glyphicon glyphicon-plus"></span>
                </button>
                </span>
              </div>
          </div>
        </div>
                </div>
            </form>
            <div id="image_result"></div>
        </div>
        <div class="modal-footer">       
        </div>
    </div>
</div>