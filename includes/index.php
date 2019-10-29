<?php
	$page 	= $_GET['paget'] ?? 1;
	$orderBy = $_GET['orderby'] ?? 'id';
	$order 	= $_GET['order'] ?? 'desc';

	$ratings 	= Rating::getAll($page, $orderBy, $order);

	$mainUrl = '/wp-admin/admin.php?page=wp_ratingpost_plugin%2Fincludes%2F';

	$params = [
		'post_id' => 'ID статьи', 
		'positive' => 'Положительных', 
		'negative' => 'Отрицательных', 
		'comment' => 'Комментарии'
	];
?>

<div class="wrap" id="wp-media-grid" data-search="">
	<div>
		<h1 style="display: inline-block;">Рейтинги</h1>
	</div>
	
	<?php if(!empty($ratings)): ?>

	<table style="margin-top: 30px;" class="wp-list-table widefat fixed striped users">
		<thead>
		<tr>
			<?php foreach($params as $key => $value): ?>
			<th scope="col" id="<?= $key ?>" class="manage-column column-username column-primary sortable <?= ($key == $orderBy && $order == 'asc') ? 'desc' : 'asc' ?>">
				<a href="/wp-admin/admin.php?page=wp_ratingpost_plugin%2Fincludes%2Findex.php&amp;orderby=<?= $key ?>&amp;order=<?= ($key == $orderBy && $order == 'asc') ? 'desc' : 'asc' ?>">
					<span><?= $value ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<?php endforeach; ?>
		</tr>
		</thead>

		<tbody id="the-list" data-wp-lists="list:user">
			
		<?php foreach($ratings as $rating): ?>
			<tr>
				<td>
					<a title="<?= $rating->post_title ?>" target="_blank" href="/<?= $rating->url ?>"><strong><?= $rating->post_id ?> - <?= $rating->post_title ?></strong></a>
					
				</td>
				<td><?= $rating->positive ?></td>
				<td><?= $rating->negative ?></td>
				<td><?= implode('<br>', json_decode($rating->comment)) ?></td>
			</tr>
		<?php endforeach; ?>

	</table>

	<?php else: ?>
	<div style="margin-top: 70px;">
		<span>Ничего не найдено</span>
	</div>
	<?php endif;?>
</div>