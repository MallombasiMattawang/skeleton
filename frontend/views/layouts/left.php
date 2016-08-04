<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
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
					['label' => 'Auth', 'options' => ['class' => 'header']],
					[
						'label'		 => 'Register',
						'url'		 => ['/user/registration/register'],
						'visible'	 => Yii::$app->user->isGuest,
					],
					[
						'label'		 => 'Sign in',
						'url'		 => ['/user/security/login'],
						'visible'	 => Yii::$app->user->isGuest,
					],
					[
						'label'			 => 'Sign out ( ' . ((Yii::$app->user->isGuest == FALSE) ? Yii::$app->user->identity->username : '') . ')',
						'url'			 => ['/user/security/logout'],
						'linkOptions'	 => ['data-method' => 'post'],
						'visible'		 => (Yii::$app->user->isGuest == FALSE),
					],
					['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
					//*/
					//['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
					//['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],
					['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
					[
						'label'	 => 'Same tools',
						'icon'	 => 'fa fa-share',
						'url'	 => '#',
						'items'	 => [
							['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii'],],
							['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug'],],
							[
								'label'	 => 'Level One',
								'icon'	 => 'fa fa-circle-o',
								'url'	 => '#',
								'items'	 => [
									['label' => 'Level Two', 'icon' => 'fa fa-circle-o', 'url' => '#',],
									[
										'label'	 => 'Level Two',
										'icon'	 => 'fa fa-circle-o',
										'url'	 => '#',
										'items'	 => [
											['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
											['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
										],
									],
								],
							],
						],
					],
					//*/
					[
						'label'	 => 'Master',
						'icon'	 => 'fa fa-file',
						'url'	 => '#',
						'items'	 => [
							[
								'label'	 => 'Religion',
								'icon'	 => 'fa fa-file-code-o',
								'url'	 => ['/religion'],
							],
						],
					],
					[
						'label'	 => 'Region',
						'icon'	 => 'fa fa-share',
						'url'	 => '#',
						'items'	 => [
							[
								'label'	 => 'Country',
								'icon'	 => 'fa fa-file-code-o',
								'url'	 => ['/rgn-country'],
							],
							[
								'label'	 => 'Province',
								'icon'	 => 'fa fa-file-code-o',
								'url'	 => ['/rgn-province'],
							],
							[
								'label'	 => 'City',
								'icon'	 => 'fa fa-file-code-o',
								'url'	 => ['/rgn-city'],
							],
							[
								'label'	 => 'District',
								'icon'	 => 'fa fa-file-code-o',
								'url'	 => ['/rgn-district'],
							],
							[
								'label'	 => 'Subdistrict',
								'icon'	 => 'fa fa-file-code-o',
								'url'	 => ['/rgn-subdistrict'],
							],
							[
								'label'	 => 'Postcode',
								'icon'	 => 'fa fa-file-code-o',
								'url'	 => ['/rgn-postcode'],
							],
						],
					],
					[
						'label'	 => 'Modules',
						'icon'	 => 'fa fa-share',
						'url'	 => '#',
						'items'	 => [
							[
								'label'	 => 'Region',
								'icon'	 => 'fa fa-circle-o',
								'url'	 => '/region',
								'items'	 => [
									['label' => 'Country', 'icon' => 'fa fa-circle-o', 'url' => ['/region/country'],],
									['label' => 'Province', 'icon' => 'fa fa-circle-o', 'url' => ['/region/province'],],
									['label' => 'City', 'icon' => 'fa fa-circle-o', 'url' => ['/region/city'],],
									['label' => 'District', 'icon' => 'fa fa-circle-o', 'url' => ['/region/district'],],
									['label' => 'Subdistrict', 'icon' => 'fa fa-circle-o', 'url' => ['/region/subdistrict'],],
									['label' => 'Postcode', 'icon' => 'fa fa-circle-o', 'url' => ['/region/postcode'],],
								],
							],
						],
					],
				],
			]
		)

		?>

    </section>

</aside>
