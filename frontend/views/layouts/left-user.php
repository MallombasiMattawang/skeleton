<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">

				<?=

				\common\widgets\Gravatar::widget([
					'email'		 => Yii::$app->user->identity->email,
					'size'		 => 45,
					'options'	 => [
						'class'	 => 'img-circle',
						'alt'	 => 'Gravatar image',
						'title'	 => 'Gravatar image',
					],
					'linkUrl'	 => FALSE,
				]);

				?>

            </div>
            <div class="pull-left info">
                <p>
					<?= Yii::$app->user->identity->username; ?>
				</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form ->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
				<span class="input-group-btn">
					<button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
					</button>
				</span>
            </div>
        </form>
        <!-- /.search form -->

		<?=

		dmstr\widgets\Menu::widget(
			[
				'options'	 => ['class' => 'sidebar-menu'],
				'items'		 => [
					['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],
					
				],
			]
		)

		?>

    </section>

</aside>
