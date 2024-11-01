<?php
if (!isset($_GET['order_id'])) {
    $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
    $url_sort = get_admin_url() . 'admin.php?page=iol-translation&pagenum=' . $pagenum;
    $url = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
    global $wpdb;
    ?>
    <script>
    <?php
    if (isset($_GET['deleted_id'])) {
        if (!Iol_Translation_M_Iol_Translation_Order_Peer::checkOrderIsPay($_GET['deleted_id'])) {
            Iol_Translation_M_Iol_Translation_Order_Peer::deleteOrder($_GET['deleted_id']);
        }
        $tmp_array = explode('&', $url);
        array_pop($tmp_array);
        $tmp_url = implode('&', $tmp_array);
        ?>
                location.href='<?php echo $tmp_url; ?>';
    <?php } ?>
    </script>
    <?php
    $order_by = isset($_GET['orderby']) ? $_GET['orderby'] : 'created_at';
    $sortorder = isset($_GET['order']) ? $_GET['order'] : 'desc';

    $sort = $sortorder == 'asc' ? 'desc' : 'asc';
    $limit = 20;
    $offset = ( $pagenum - 1 ) * $limit;
    $orders = $wpdb->get_results("SELECT * FROM {$wpdb->iol_translation_order} order by " . $order_by . " " . $sortorder . " LIMIT $offset, $limit ");
    ?>
    <div class="wrap translate_order_manage">
        <h2><?php echo Iol_Translation_U::__('Translation Orders') ?></h2>
        <form id="posts-filter" method="post" action="">
            <table class="wp-list-table widefat fixed posts iol-translation-table" cellspacing="0" style="margin-top:30px;">
                <thead>
                    <tr>
                        <th class="manage-column" width="5%">
                            <a href="<?php echo $url_sort . '&orderby=id&order=' . $sort ?>"><?php echo Iol_Translation_U::__('Id') ?>
                                <?php
                                if (isset($_GET['orderby'])) {
                                    if ($_GET['orderby'] == 'id') {
                                        ?> <span class="<?php echo $sortorder == 'asc' ? 'sort_asc' : 'sort_desc' ?>"></span>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <span class="sort_desc"></span>
                                <?php } ?>
                            </a>
                        </th>
                        <th class="manage-column" width="25%">
                            <a href="<?php echo $url_sort . '&orderby=order_number&order=' . $sort ?>"><?php echo Iol_Translation_U::__('Order ID') ?>
                                <?php
                                if (isset($_GET['orderby'])) {
                                    if ($_GET['orderby'] == 'order_number') {
                                        ?> <span class="<?php echo $sortorder == 'asc' ? 'sort_asc' : 'sort_desc' ?>"></span>
                                        <?php
                                    }
                                }
                                ?>
                            </a>
                        </th>
                        <th class="manage-column" width="15%">
                            <a href="<?php echo $url_sort . '&orderby=created_at&order=' . $sort ?>"><?php echo Iol_Translation_U::__('Created At') ?>
                                <?php
                                if (isset($_GET['orderby'])) {
                                    if ($_GET['orderby'] == 'created_at') {
                                        ?> <span class="<?php echo $sortorder == 'asc' ? 'sort_asc' : 'sort_desc' ?>"></span>
                                        <?php
                                    }
                                }
                                ?>
                            </a>
                        </th>
                        <th class="manage-column" width="10%">
                            <a href="<?php echo $url_sort . '&orderby=payment_status&order=' . $sort ?>">
                                <?php echo Iol_Translation_U::__('Payment Status') ?>
                                <?php
                                if (isset($_GET['orderby'])) {
                                    if ($_GET['orderby'] == 'payment_status') {
                                        ?> <span class="<?php echo $sortorder == 'asc' ? 'sort_asc' : 'sort_desc' ?>"></span>
                                        <?php
                                    }
                                }
                                ?>
                            </a>
                        </th>
                        <th class="manage-column" width="10%">
                            <a href="<?php echo $url_sort . '&orderby=translation_status&order=' . $sort ?>"><?php echo Iol_Translation_U::__('Translation Status') ?>
                                <?php
                                if (isset($_GET['orderby'])) {
                                    if ($_GET['orderby'] == 'translation_status') {
                                        ?> <span class="<?php echo $sortorder == 'asc' ? 'sort_asc' : 'sort_desc' ?>"></span>
                                        <?php
                                    }
                                }
                                ?>
                            </a>
                        </th>
                        <th class="manage-column" width="10%">
                            <a href="<?php echo $url_sort . '&orderby=price&order=' . $sort ?>"><?php echo Iol_Translation_U::__('Price') ?>
                                <?php
                                if (isset($_GET['orderby'])) {
                                    if ($_GET['orderby'] == 'price') {
                                        ?> <span class="<?php echo $sortorder == 'asc' ? 'sort_asc' : 'sort_desc' ?>"></span>
                                        <?php
                                    }
                                }
                                ?>
                            </a>
                        </th>
                        <th class="manage-column" width="15%">
                            <a href="<?php echo $url_sort . '&orderby=level&order=' . $sort ?>"><?php echo Iol_Translation_U::__('Level') ?>
                                <?php
                                if (isset($_GET['orderby'])) {
                                    if ($_GET['orderby'] == 'level') {
                                        ?> <span class="<?php echo $sortorder == 'asc' ? 'sort_asc' : 'sort_desc' ?>"></span>
                                        <?php
                                    }
                                }
                                ?>
                            </a>
                        </th>
                        <th class="manage-column" width="10%"><?php echo Iol_Translation_U::__('Action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($orders as $order) {
                        ?>
                        <tr class="table_body <?php echo $i % 2 == 0 ? 'alternate' : ''; ?>">
                            <td><?php echo $order->id; ?></td>
                            <td><a href="<?php echo $url . '&order_id=' . $order->id; ?>"><?php echo $order->order_number; ?></a></td>
                            <td><?php echo date('Y-m-d H:i:s', $order->created_at); ?></td>
                            <td><?php echo $order->payment_status == 1 ? Iol_Translation_U::__('unpaid') : Iol_Translation_U::__('paid'); ?></td>
                            <td><?php echo $order->translation_status == 1 ? Iol_Translation_U::__('pending') : Iol_Translation_U::__('done'); ?></td>
                            <td><?php echo $order->price; ?></td>
                            <td><?php echo $order->level == 1 ? Iol_Translation_U::__('Professional translation') : Iol_Translation_U::__('Standard translation'); ?></td>
                            <td>
                                <?php if (!Iol_Translation_M_Iol_Translation_Configuration_Peer::checkUserIsLogout()) { ?>
                                    <?php if (!Iol_Translation_M_Iol_Translation_Order_Peer::checkOrderIsPay($order->id)) { ?>
                                        <a delete_id="<?php echo $order->id; ?>" class="delete_order" href="javascript:void(0);" ><?php echo Iol_Translation_U::__('Delete') ?></a>
                                    <?php } else { ?>
                                        <a href="javascript:void(0);"><?php echo Iol_Translation_U::__('Delete') ?></a>
                                    <?php } ?>&nbsp;&nbsp;
                                    <?php if (!Iol_Translation_M_Iol_Translation_Order_Peer::checkOrderIsPay($order->id)) { ?>
                                        <a target="_blank" href="<?php echo $order->pay_url ?>" style=""><?php echo Iol_Translation_U::__('Pay') ?></a>
                                        <?php
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </form>
        <?php
        $total = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->iol_translation_order}");
        $num_of_pages = ceil($total / $limit);
        $page_links = paginate_links(array(
            'base' => add_query_arg('pagenum', '%#%'),
            'format' => '',
            'prev_text' => __('&laquo;', 'aag'),
            'next_text' => __('&raquo;', 'aag'),
            'total' => $num_of_pages,
            'current' => $pagenum
                ));

        if ($page_links) {
            echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
        }
        ?>
    </div>
    <script>
        jQuery(document).ready(function(){
            jQuery('.iol-translation-table .delete_order').click(function(){
                var id = jQuery(this).attr('delete_id');
                if(confirm('<?php echo Iol_Translation_U::__('After you delete the current record') . ',' . Iol_Translation_U::__('is not recoverable') . '.' . Iol_Translation_U::__('Are you sure to delete the current record'); ?> ?')){
                    location.href='<?php echo $url . '&deleted_id=' ?>'+id; 
                }
            })
        })
    </script>

<?php } else { ?>
    <?php Iol_Translation_U::loadAction('admin_show_order_details'); ?>
<?php } ?>
