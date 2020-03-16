<table class="table table-bordered">
    <tr>
        <th>S No.</th>
        <th>Image</th>
        <th>Manufacturer</th>
        <th>Rim ID</th>
        <th>ET</th>
        <th>Rim Size</th>
        <th>Type</th>
       
        <th>WorkmanShip</th>
       <th>Type  Descripttion</th>
        <th>Actions</th>
    </tr>
    @forelse($get_rims as $rim)
       @php
           $rim = $obj->rim_list($rim); 
       @endphp
        <tr>
            <td><?php echo $loop->iteration; ?></td>
            <td><img src="<?php if(!empty($rim->image)) echo $rim->image; ?>" style="width:50px;"></td>
            <td><?php if(!empty($rim->maker_name)) echo $rim->maker_name; ?> </td>
            <td><?php if(!empty($rim->rim_id)) echo $rim->rim_id; ?></td>
            <td><?php if(!empty($rim->ET)) echo $rim->ET; ?></td>
            <td><?php if(!empty($rim->size)) echo $rim->size; ?></td>
            <td><?php if(!empty($rim->rim_type)) echo $rim->rim_type; ?></td>
            <th><?php if(!empty($rim->workmanship)) echo $rim->workmanship; ?></th>
             <th><?php if(!empty($rim->typeDescription)) echo $rim->typeDescription; ?></th>
            <td><a href='{{  url("rim/edit_rim_details/$rim->id") }}' class="btn btn-primary"><span class="fa fa-edit">&nbsp;Edit</span></a>&nbsp;&nbsp;
           <!--/* <a href="javascript::void()" data-rimid="{{ $rim->id }}" class="btn btn-warning riminfo"><span class="fa fa-info-circle">&nbsp;Info</span></a>*/-->
            </td>
        </tr>
    @empty
        <tr>  
            <td colspan="7">No record found !!!</td>
        </tr>
    @endforelse
</table>