<?php use Carbon\Carbon;

$title = 'Home page'; ?>
<?php ob_start() ?>
    <section class="content">
        <?php if ($history->count() > 0) : ?>
            <table class="u-full-width">
                <thead>
                <tr>
                    <th>ProductName</th>
                    <th>Email client</th>
                    <th>Number card</th>
                    <th>Price</th>
                    <th>total</th>
                    <th>quantity</th>
                    <th>date command</th>
                    <th>status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($histories as $history): ?>
                    <tr>
                    <td><?php echo ($p = $product->find($history->product_id)) ? $p->title : 'no name'; ?></td>
                    <td><?php echo ($c = $customer->find($history->customer_id)) ? $c->email : 'no email'; ?></td>
                    <td><?php echo (!empty($c)) ? $c->number_card : 'no card number'; ?></td>
                    <td><?php echo $history->price; ?></td>
                    <td><?php echo $history->total; ?></td>
                    <td><?php echo $history->quantity; ?></td>
                    <td><?php echo Carbon::parse($history->commanded_at)->format('d/m/Y h:i:s'); ?></td>
                    <td><?php echo $history->status; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Sorry no command</p>
        <?php endif; ?>
    </section>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/../layouts/master.php' ?>