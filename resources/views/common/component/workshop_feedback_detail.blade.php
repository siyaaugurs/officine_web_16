<?php 
// echo "<pre>";
// print_r($images);exit;
?>
<div style="overflow:auto">
    <table class="table table-border">
        <tr>
            <th>Feedback By</th>
            <td>{{ strtoupper($feedback->f_name) }}</td>
        </tr>
        <tr>
            <th>Ratings</th>
            <td>{{ $feedback->rating }}</td>
        </tr>
        <tr>
            <th>Feedback Comments</th>
            <td>{{ $feedback->comments }}</td>
        </tr>
        <tr>
            <th>Posted At</th>
            <td>{{ $feedback->created_at }}</td>
        </tr>
        <tr>
            <th>Images</th>
            <td>
                <div class="row">
                    @forelse($images as $images)
                    <div class="col-4"><img class="img-thumbnail" style="height:100px;width:120px;" src="<?php echo $images->image_url ;?>"></div>
                    @empty
                        N/A
                    @endforelse 
                </div>
            </td>
        </tr>
    </table>
</div>