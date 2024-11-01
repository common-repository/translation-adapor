<?php
$order_id = $_GET['order_id'];
$order = Iol_Translation_M_Iol_Translation_Order_Peer::retrieveByPK($order_id);
?>
<div class="wrap">
    <h2><?php echo Iol_Translation_U::__('Translation') ?> : <?php echo Iol_Translation_U::__('order') ?> 20140228215919355 </h2><br/>
    <div class="order_details" style="width: 100%">
        <div id="namediv" class="stuffbox" style="width:100%">
            <div class="inside">
                <table class="order_details_form_list" width="100%" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="label" width="15%"><label><?php echo Iol_Translation_U::__('Order ID') ?></label></td>
                            <td width="15%"><?php echo $order->order_number; ?></td>
                            <td width="70%">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="label"><label><?php echo Iol_Translation_U::__('Translation Level') ?></label></td>
                            <td><?php echo $order->level == 1 ? Iol_Translation_U::__('Professional translation') : Iol_Translation_U::__('Standard translation'); ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="label"><label><?php echo Iol_Translation_U::__('Price') ?></label></td>
                            <td><?php echo $order->price; ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="label"><label><?php echo Iol_Translation_U::__('Word Count') ?></label></td>
                            <td><?php echo $order->word_count; ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="label"><label><?php echo Iol_Translation_U::__('Payment Status') ?></label></td>
                            <td><?php echo $order->payment_status == 1 ? Iol_Translation_U::__('unpaid') : Iol_Translation_U::__('paid'); ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="label"><label><?php echo Iol_Translation_U::__('Translation Status') ?></label></td>
                            <td><?php echo $order->translation_status == 1 ? Iol_Translation_U::__('pending') : Iol_Translation_U::__('done'); ?></td>
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    global $wpdb;
    $pagenum = isset($_GET['m_pagenum']) ? absint($_GET['m_pagenum']) : 1;
    $limit = 20;
    $offset = ( $pagenum - 1 ) * $limit;
    $manuscripts = $wpdb->get_results("SELECT * FROM {$wpdb->iol_translation_manuscript} where iol_translation_order_id = " . $order_id . " LIMIT $offset, $limit ");
    ?>
    <form id="posts-filter" method="post" action="">
        <table class="wp-list-table widefat fixed posts iol-translation-table" cellspacing="0" style="margin-top:30px;">
            <thead>
                <tr>
                    <th width="5%" class="manage-column"><?php echo Iol_Translation_U::__('ID') ?></th>
                    <th width="20%" class="manage-column"><?php echo Iol_Translation_U::__('Manuscript ID') ?></th>
                    <th width="5%" class="manage-column"><?php echo Iol_Translation_U::__('Type') ?></th>
                    <th width="10%" class="manage-column"><?php echo Iol_Translation_U::__('Word Count') ?></th>
                    <th width="10%" class="manage-column"><?php echo Iol_Translation_U::__('Source Language') ?></th>
                    <th width="10%" class="manage-column"><?php echo Iol_Translation_U::__('Target Language') ?></th>
                    <th width="10%" class="manage-column"><?php echo Iol_Translation_U::__('Price') ?></th>
                    <th width="15%" class="manage-column"><?php echo Iol_Translation_U::__('Created At') ?></th>
                    <th width="15%" class="manage-column"><?php echo Iol_Translation_U::__('Translation Time') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($manuscripts as $manuscript) {
                    ?>
                    <tr class="<?php echo $i % 2 == 0 ? 'alternate' : ''; ?>">
                        <td><?php echo $manuscript->id ?></td>
                        <td><?php echo $manuscript->manuscript_number; ?></td>
                        <td><?php echo Iol_Translation_U::getWhichModelTranslated($manuscript->iol_translation_type_id); ?></td>
                        <td><?php echo $manuscript->word_count; ?></td>
                        <td><?php echo $manuscript->source_language_code; ?></td>
                        <td><?php echo $manuscript->target_language_code; ?></td>
                        <td><?php echo $manuscript->price; ?></td>
                        <td><?php echo date('Y-m-d H:i:s', $manuscript->created_at); ?></td>
                        <td><?php echo $manuscript->transed_at; ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </form>
    <?php
    $total = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->iol_translation_manuscript} where iol_translation_order_id=".$order_id);
    $num_of_pages = ceil($total / $limit);
    $page_links = paginate_links(array(
        'base' => add_query_arg('m_pagenum', '%#%'),
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

    <?php
    $tmp = explode('&', $_SERVER['QUERY_STRING']);
    array_pop($tmp);
    $tmp_url = $_SERVER['PHP_SELF'] . '?' . implode('&', $tmp);
    ?>
    <p class="submit">
        <a href="<?php echo $tmp_url; ?>"><input id="Back" class="button button-primary" type="button" value="<?php echo Iol_Translation_U::__('Back') ?>" name="Back"></a>
    </p>

</div>


