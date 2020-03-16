<table class="table">
    <tr>
        <!-- <th>Product Item Number</th> -->
        <th>N3 Category</th>
    </tr>
        @forelse ($product as $products)
            <tr>
                <!-- <td><?= $products->products_name?></td> -->
                <td><?= $products->item_name ." ".$products->front_rear." ". $products->left_right; ?></td>
            </tr>
        @empty
        @endforelse
        <?php
        if($get_compatible_product->count() > 0) {
            foreach ($get_compatible_product as $product) {
                if($product->item == 0) {
                    if($product->all_item == 1) {
                        if($product->all_sub_group == 1) {
                            if($product->all_group == 1) {
                                $get_n3_category = "All N3 Category";
                                ?>
                                    <tr>
                                        <!-- <td>P</td> -->
                                        <td>All N3 Category</td>
                                    </tr>
                                <?php
                            } else {
                                $get_n1_category = DB::table('products_groups')->where([['id', '=', $product->group], ['parent_id', '=', 0]])->first();
                                $get_all_n2_category = DB::table('products_groups')->where([['parent_id', '=', $get_n1_category->id], ['deleted_at', '=', NULL]])->get();
                                if($get_all_n2_category->count() > 0) {
                                    $n2_category_arr = $get_all_n2_category->pluck('id')->all();
                                    $get_n3_category = \App\ProductsGroupsItem::whereIn('products_groups_id', $n2_category_arr)->where('deleted_at', NULL)->get();
                                    foreach($get_n3_category as $n3_cat) {
                                        ?>
                                            <tr>
                                                <!-- <td>P</td> -->
                                                <td><?= $n3_cat->item ." ".$n3_cat->front_rear." ". $n3_cat->left_right; ?></td>
                                            </tr>
                                        <?php
                                    }
                                }
                            }
                        } else {
                            $get_n2_category = DB::table('products_groups')->where([['id', '=', $product->sub_group], ['parent_id', '!=', 0]])->first();
                            $get_n3_category = \App\ProductsGroupsItem::where([['products_groups_id', '=', $get_n2_category->id], ['deleted_at', '=', NULL]])->get();
                                foreach($get_n3_category as $n3_cat) {
                                    ?>
                                        <tr>
                                            <!-- <td>P</td> -->
                                            <td><?= $n3_cat->item ." ".$n3_cat->front_rear." ". $n3_cat->left_right; ?></td>
                                        </tr>
                                    <?php
                                }
                        }
                    }
                } else {
                    $get_n3_category = \App\ProductsGroupsItem::get_product_n3_category($product->item);
                    foreach($get_n3_category as $n3_cat) {
                        ?>
                            <tr>
                                <!-- <td>P</td> -->
                                <td><?= $n3_cat->item ." ".$n3_cat->front_rear." ". $n3_cat->left_right; ?></td>
                            </tr>
                        <?php
                    }
                    
                }
            }
        } 
        ?>
        
</table>