<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
   <div class="page-container">
   <!--/content-inner-->
<div class="left-content">
	   <div class="mother-grid-inner">
             <!--header start here-->
				<div class="header-main">
					<div class="logo-w3-agile">
								<h1><a href="/">Pooled</a></h1>
							</div>
					<div class="w3layouts-left">

							<!--search-box-->
								<div class="w3-search-box">
									<form action="#" method="post">
										<input type="text" placeholder="Search..." required="">
										<input type="submit" value="">
									</form>
								</div><!--//end-search-box-->
							<div class="clearfix"> </div>
						 </div>
						 <div class="w3layouts-right">
							<div class="profile_details_left"><!--notifications of menu start -->
								<ul class="nofitications-dropdown">
									<li class="dropdown head-dpdn">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-envelope"></i><span class="badge">3</span></a>
										<ul class="dropdown-menu">
											<li>
												<div class="notification_header">
													<h3>You have 3 new messages</h3>
												</div>
											</li>
											<li><a href="#">
											   <div class="user_img"><img src="images/in11.jpg" alt=""></div>
											   <div class="notification_desc">
												<p>Lorem ipsum dolor</p>
												<p><span>1 hour ago</span></p>
												</div>
											   <div class="clearfix"></div>
											</a></li>
											<li class="odd"><a href="#">
												<div class="user_img"><img src="images/in10.jpg" alt=""></div>
											   <div class="notification_desc">
												<p>Lorem ipsum dolor </p>
												<p><span>1 hour ago</span></p>
												</div>
											  <div class="clearfix"></div>
											</a></li>
											<li><a href="#">
											   <div class="user_img"><img src="images/in9.jpg" alt=""></div>
											   <div class="notification_desc">
												<p>Lorem ipsum dolor</p>
												<p><span>1 hour ago</span></p>
												</div>
											   <div class="clearfix"></div>
											</a></li>
											<li>
												<div class="notification_bottom">
													<a href="#">See all messages</a>
												</div>
											</li>
										</ul>
									</li>
									<li class="dropdown head-dpdn">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell"></i><span class="badge blue">3</span></a>
										<ul class="dropdown-menu">
											<li>
												<div class="notification_header">
													<h3>You have 3 new notification</h3>
												</div>
											</li>
											<li><a href="#">
												<div class="user_img"><img src="images/in8.jpg" alt=""></div>
											   <div class="notification_desc">
												<p>Lorem ipsum dolor</p>
												<p><span>1 hour ago</span></p>
												</div>
											  <div class="clearfix"></div>
											 </a></li>
											 <li class="odd"><a href="#">
												<div class="user_img"><img src="images/in6.jpg" alt=""></div>
											   <div class="notification_desc">
												<p>Lorem ipsum dolor</p>
												<p><span>1 hour ago</span></p>
												</div>
											   <div class="clearfix"></div>
											 </a></li>
											 <li><a href="#">
												<div class="user_img"><img src="images/in7.jpg" alt=""></div>
											   <div class="notification_desc">
												<p>Lorem ipsum dolor</p>
												<p><span>1 hour ago</span></p>
												</div>
											   <div class="clearfix"></div>
											 </a></li>
											 <li>
												<div class="notification_bottom">
													<a href="#">See all notifications</a>
												</div>
											</li>
										</ul>
									</li>
									<li class="dropdown head-dpdn">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-tasks"></i><span class="badge blue1">9</span></a>
										<ul class="dropdown-menu">
											<li>
												<div class="notification_header">
													<h3>You have 8 pending task</h3>
												</div>
											</li>
											<li><a href="#">
												<div class="task-info">
													<span class="task-desc">Database update</span><span class="percentage">40%</span>
													<div class="clearfix"></div>
												</div>
												<div class="progress progress-striped active">
													<div class="bar yellow" style="width:40%;"></div>
												</div>
											</a></li>
											<li><a href="#">
												<div class="task-info">
													<span class="task-desc">Dashboard done</span><span class="percentage">90%</span>
												   <div class="clearfix"></div>
												</div>
												<div class="progress progress-striped active">
													 <div class="bar green" style="width:90%;"></div>
												</div>
											</a></li>
											<li><a href="#">
												<div class="task-info">
													<span class="task-desc">Mobile App</span><span class="percentage">33%</span>
													<div class="clearfix"></div>
												</div>
											   <div class="progress progress-striped active">
													 <div class="bar red" style="width: 33%;"></div>
												</div>
											</a></li>
											<li><a href="#">
												<div class="task-info">
													<span class="task-desc">Issues fixed</span><span class="percentage">80%</span>
												   <div class="clearfix"></div>
												</div>
												<div class="progress progress-striped active">
													 <div class="bar  blue" style="width: 80%;"></div>
												</div>
											</a></li>
											<li>
												<div class="notification_bottom">
													<a href="#">See all pending tasks</a>
												</div>
											</li>
										</ul>
									</li>
									<div class="clearfix"> </div>
								</ul>
								<div class="clearfix"> </div>
							</div>
							<!--notification menu end -->

							<div class="clearfix"> </div>
						</div>
						<div class="profile_details w3l">
								<ul>
									<li class="dropdown profile_details_drop">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
											<div class="profile_img">
												<span class="prfil-img"><img src="images/in4.jpg" alt=""> </span>
												<div class="user-name">
													<p><?= Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname?></p>
													<span>Administrator</span>
												</div>
												<i class="fa fa-angle-down"></i>
												<i class="fa fa-angle-up"></i>
												<div class="clearfix"></div>
											</div>
										</a>
										<ul class="dropdown-menu drp-mnu">
											<li> <a href="#"><i class="fa fa-cog"></i> Settings</a> </li>
											<li> <a href="#"><i class="fa fa-user"></i> Profile</a> </li>
											<li> <?= Html::a("<i class=\"fa fa-sign-out\"></i>Выход", '/user/default/logout', [
                                                        'data' => [
                                                            'method' => 'post'
                                                        ],
                                                    ]
                                                );?></li>
										</ul>
									</li>
								</ul>
							</div>

				     <div class="clearfix"> </div>
				</div>
<!--heder end here-->
		<ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a> <i class="fa fa-angle-right"></i></li>
            </ol>
       <?= $content; ?>

        <!-- script-for sticky-nav -->
           <script>
           var navoffeset=$(".header-main").offset().top;
           $(window).scroll(function(){
           var scrollpos=$(window).scrollTop();
           if(scrollpos >=navoffeset){
           $(".header-main").addClass("fixed");
           }else{
           $(".header-main").removeClass("fixed");
           }
           });
           </script>

		<!-- /script-for sticky-nav -->
<!--inner block start here-->
<div class="inner-block">

</div>
<!--inner block end here-->
<!--copy rights start here-->
<div class="copyrights">
	 <p>© 2016 Pooled. All Rights Reserved | Design by  <a href="http://w3layouts.com/" target="_blank">W3layouts</a> </p>
</div>
<!--COPY rights end here-->
</div>
</div>
  <!--//content-inner-->
			<!--/sidebar-menu-->
				<div class="sidebar-menu">
					<header class="logo1">
						<a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a>
					</header>
						<div style="border-top:1px ridge rgba(255, 255, 255, 0.15)"></div>
                           <div class="menu">
                               <ul id="menu" >
                                   <li><a href="/main/default/index"><i class="fa fa-tachometer"></i> <span>Главная</span><div class="clearfix"></div></a></li>
                                   <li id="menu-academico" ><a href="#"><i class="fa fa-file-text-o"></i>  <span>Документы</span> <span class="fa fa-angle-right pull-right" </span><div class="clearfix"></div></a>
                                       <ul id="menu-academico-sub" >
                                           <li id="menu-academico-boletim" ><a href="/billing/index">Расчет потребления</a></li>
                                           <li id="menu-academico-avaliacoes" ><a href="/billing/renter">Расчет по арендатору</a></li>
                                       </ul>
                                   </li>
                                   <li id="menu-academico" ><a href="#"><i class="fa fa-book" aria-hidden="true"></i><span> Контакты</span> <span class="fa fa-angle-right" style="float: right"></span><div class="clearfix"></div></a>
                                       <ul id="menu-academico-sub" >
                                           <li id="menu-academico-boletim" ><a href="/main/renter">Арендаторы</a></li>
                                           <li id="menu-academico-avaliacoes" ><a href="/main/department">Наши юрлица</a></li>
                                       </ul>
                                   </li>
                                   <li id="menu-academico" ><a href="#"><i class="fa fa-gear"></i>  <span>Маркетинг</span> <span class="fa fa-angle-right" style="float: right"></span><div class="clearfix"></div></a>
                                       <ul id="menu-academico-sub" >
                                           <li id="menu-academico-boletim" ><a href="/ankets">Анкеты</a></li>
                                           <li id="menu-academico-avaliacoes" ><a href="/refbooks">Справочники</a></li>
                                       </ul>
                                   </li>
                                   <li id="menu-academico" ><a href="#"><i class="fa fa-bar-chart"></i>  <span>Отчеты</span> <span class="fa fa-angle-right" style="float: right"></span><div class="clearfix"></div></a>
                                       <ul id="menu-academico-sub" >
                                           <li id="menu-academico-boletim" ><a href="/reports/ankets">Анкетирование</a></li>
                                           <li id="menu-academico-avaliacoes" ><a href="/reports/clients">Посещаемость выставки</a></li>
                                           <li id="menu-academico-avaliacoes" ><a href="/reports/renters">Присутствие на выставке</a></li>
                                           <li id="menu-academico-avaliacoes" ><a href="/reports/energy">Потребление эл. энергии</a></li>
                                       </ul>
                                   </li>
                                   <li id="menu-academico" ><a href="#"><i class="fa fa-table"></i>  <span>Посещение</span> <span class="fa fa-angle-right" style="float: right"></span><div class="clearfix"></div></a>
                                       <ul id="menu-academico-sub" >
                                           <li id="menu-academico-boletim" ><a href="/registrations/renters">Присутствие на выставке</a></li>
                                           <li id="menu-academico-avaliacoes" ><a href="/registrations/clients">Посещение выставки</a></li>
                                       </ul>
                                   </li>
                                   <li id="menu-academico" ><a href="#"><i class="fa fa-lightbulb-o" aria-hidden="true"></i><span> Энергоучет</span> <span class="fa fa-angle-right" style="float: right"></span><div class="clearfix"></div></a>
                                       <ul id="menu-academico-sub" >
                                           <li id="menu-academico-boletim" ><a href="/energy/main">Счетчики общие</a></li>
                                           <li id="menu-academico-avaliacoes" ><a href="/energy/renters">Счетчики арендаторов</a></li>
                                           <li id="menu-academico-avaliacoes" ><a href="/energy/init">Начальные показания</a></li>
                                           <li id="menu-academico-avaliacoes" ><a href="/energy/billing">Расчет потребления</a></li>
                                       </ul>
                                   </li>
                                   <li id="menu-academico" ><a href="#"><i class="fa fa-gear" aria-hidden="true"></i><span> Настройки</span> <span class="fa fa-angle-right" style="float: right"></span><div class="clearfix"></div></a>
                                       <ul id="menu-academico-sub" >
                                           <li id="menu-academico-boletim" ><a href="/energy/main">Счетчики общие</a></li>
                                           <li id="menu-academico-avaliacoes" ><a href="/energy/renters">Счетчики арендаторов</a></li>
                                           <li id="menu-academico-avaliacoes" ><a href="/energy/init">Начальные показания</a></li>
                                           <li id="menu-academico-avaliacoes" ><a href="/energy/billing">Расчет потребления</a></li>
                                       </ul>
                                   </li>
                               </ul>
								</div>
							  </div>
							  <div class="clearfix"></div>
							</div>
							<script>
							var toggle = true;

							$(".sidebar-icon").click(function() {
                                if (toggle)
                                {
                                    $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                                    $("#menu span").css({"position":"absolute"});
							  }
                                else
                                {
                                    $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                                    setTimeout(function() {
                                        $("#menu span").css({"position":"relative"});
								}, 400);
                                }

                                toggle = !toggle;
                            });
							</script>
<!--js -->

<!-- morris JavaScript -->

<script>
$(document).ready(function() {
    //BOX BUTTON SHOW AND CLOSE
    jQuery('.small-graph-box').hover(function() {
        jQuery(this).find('.box-button').fadeIn('fast');
    }, function() {
        jQuery(this).find('.box-button').fadeOut('fast');
    });
    jQuery('.small-graph-box .box-close').click(function() {
        jQuery(this).closest('.small-graph-box').fadeOut(200);
        return false;
    });

    //CHARTS
    function gd(year, day, month) {
        return new Date(year, month - 1, day).getTime();
    }

    graphArea2 = Morris.Area({
			element: 'hero-area',
			padding: 10,
        behaveLikeLine: true,
        gridEnabled: false,
        gridLineColor: '#dddddd',
        axes: true,
        resize: true,
        smooth:true,
        pointSize: 0,
        lineWidth: 0,
        fillOpacity:0.85,
			data: [
				{period: '2014 Q1', iphone: 2668, ipad: null, itouch: 2649},
				{period: '2014 Q2', iphone: 15780, ipad: 13799, itouch: 12051},
				{period: '2014 Q3', iphone: 12920, ipad: 10975, itouch: 9910},
				{period: '2014 Q4', iphone: 8770, ipad: 6600, itouch: 6695},
				{period: '2015 Q1', iphone: 10820, ipad: 10924, itouch: 12300},
				{period: '2015 Q2', iphone: 9680, ipad: 9010, itouch: 7891},
				{period: '2015 Q3', iphone: 4830, ipad: 3805, itouch: 1598},
				{period: '2015 Q4', iphone: 15083, ipad: 8977, itouch: 5185},
				{period: '2016 Q1', iphone: 10697, ipad: 4470, itouch: 2038},
				{period: '2016 Q2', iphone: 8442, ipad: 5723, itouch: 1801}
			],
			lineColors:['#ff4a43','#a2d200','#22beef'],
			xkey: 'period',
            redraw: true,
            ykeys: ['iphone', 'ipad', 'itouch'],
            labels: ['All Visitors', 'Returning Visitors', 'Unique Visitors'],
			pointSize: 2,
			hideHover: 'auto',
			resize: true
		});


	});
	</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>